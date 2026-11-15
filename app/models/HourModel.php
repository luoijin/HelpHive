<?php
declare(strict_types=1);

/**
 * All hour logic lives here. Verified and unverified data never mix.
 */
final class HourModel
{
    public function __construct(private PDO $db) {}

    /**
     * Volunteer submits or updates claimed hours. Stays unverified/pending.
     * Ownership is enforced through the registration's volunteer_id.
     * Requires active registration, past or started event, attendance not absent,
     * and status unverified/pending/rejected.
     */
    public function claimHours(int $registrationId, int $volunteerId, float $hours): bool
    {
        $sql = 'UPDATE attendance_and_hours ah
                JOIN registrations r ON r.id = ah.registration_id
                JOIN opportunities o ON o.id = r.opportunity_id
                SET ah.hours_claimed = :h, ah.verification_status = \'pending\'
                WHERE ah.registration_id = :rid AND r.volunteer_id = :vid
                  AND r.status = \'registered\'
                  AND o.start_at <= NOW()
                  AND ah.attendance_status <> \'absent\'
                  AND ah.verification_status IN (\'unverified\',\'pending\',\'rejected\')';
        $st = $this->db->prepare($sql);
        $st->execute(['h' => $hours, 'rid' => $registrationId, 'vid' => $volunteerId]);
        return $st->rowCount() === 1;
    }

    /**
     * Update attendance status (present, absent, excused, pending).
     * Disallowed once hours are verified.
     */
    public function markAttendance(int $registrationId, int $organizationId, string $status): bool
    {
        $st = $this->db->prepare(
            'SELECT ah.verification_status, ah.attendance_status
             FROM attendance_and_hours ah
             JOIN registrations r ON r.id = ah.registration_id
             JOIN opportunities o ON o.id = r.opportunity_id
             WHERE ah.registration_id = :rid AND o.organization_id = :oid'
        );
        $st->execute(['rid' => $registrationId, 'oid' => $organizationId]);
        $row = $st->fetch();
        if (!$row) {
            return false;
        }
        if ($row['verification_status'] === 'verified') {
            return false;
        }
        if ($row['attendance_status'] === $status) {
            return true;
        }

        $up = $this->db->prepare(
            'UPDATE attendance_and_hours ah
             JOIN registrations r ON r.id = ah.registration_id
             JOIN opportunities o ON o.id = r.opportunity_id
             SET ah.attendance_status = :st
             WHERE ah.registration_id = :rid AND o.organization_id = :oid
               AND ah.verification_status <> \'verified\''
        );
        $up->execute(['st' => $status, 'rid' => $registrationId, 'oid' => $organizationId]);
        return true;
    }

    /**
     * Organization verifies hours. Only allowed when the volunteer was marked present
     * and the registration belongs to the verifying organization's opportunity.
     *
     * @return bool True if a record was verified.
     */
    public function verify(int $registrationId, int $organizationId, int $verifierUserId, float $hoursVerified): bool
    {
        $sql = 'UPDATE attendance_and_hours ah
                JOIN registrations r ON r.id = ah.registration_id
                JOIN opportunities o ON o.id = r.opportunity_id
                SET ah.hours_verified = :hv, ah.verification_status = \'verified\',
                    ah.verified_by = :uid, ah.verified_at = NOW()
                WHERE ah.registration_id = :rid AND o.organization_id = :oid
                  AND ah.attendance_status = \'present\'
                  AND ah.verification_status IN (\'unverified\',\'pending\')';
        $st = $this->db->prepare($sql);
        $st->execute(['hv' => $hoursVerified, 'uid' => $verifierUserId, 'rid' => $registrationId, 'oid' => $organizationId]);
        return $st->rowCount() === 1;
    }

    /**
     * Organization rejects hours. Restricts to unverified and pending records.
     */
    public function reject(int $registrationId, int $organizationId): bool
    {
        $sql = 'UPDATE attendance_and_hours ah
                JOIN registrations r ON r.id = ah.registration_id
                JOIN opportunities o ON o.id = r.opportunity_id
                SET ah.hours_verified = NULL, ah.verification_status = \'rejected\', ah.verified_at = NULL
                WHERE ah.registration_id = :rid AND o.organization_id = :oid
                  AND ah.verification_status IN (\'unverified\', \'pending\')';
        $st = $this->db->prepare($sql);
        $st->execute(['rid' => $registrationId, 'oid' => $organizationId]);
        return $st->rowCount() === 1;
    }

    /** @return float Official total: verified rows only, active registrations only. */
    public function verifiedTotalForVolunteer(int $volunteerId): float
    {
        $st = $this->db->prepare(
            'SELECT COALESCE(SUM(ah.hours_verified),0)
             FROM attendance_and_hours ah
             JOIN registrations r ON r.id = ah.registration_id
             WHERE r.volunteer_id = :vid AND r.status = \'registered\' AND ah.verification_status = \'verified\''
        );
        $st->execute(['vid' => $volunteerId]);
        return (float) $st->fetchColumn();
    }

    /** @return float Display-only pending total, always shown separately in the UI. */
    public function pendingTotalForVolunteer(int $volunteerId): float
    {
        $st = $this->db->prepare(
            'SELECT COALESCE(SUM(ah.hours_claimed),0)
             FROM attendance_and_hours ah
             JOIN registrations r ON r.id = ah.registration_id
             WHERE r.volunteer_id = :vid AND r.status = \'registered\' AND ah.verification_status IN (\'unverified\',\'pending\')'
        );
        $st->execute(['vid' => $volunteerId]);
        return (float) $st->fetchColumn();
    }

    /** Total verified hours across entire platform (for admin). */
    public function platformTotalVerified(): float
    {
        $val = $this->db->query(
            'SELECT COALESCE(SUM(ah.hours_verified),0)
             FROM attendance_and_hours ah
             JOIN registrations r ON r.id = ah.registration_id
             WHERE r.status = \'registered\' AND ah.verification_status = \'verified\''
        )->fetchColumn();
        return (float) $val;
    }

    /** Total verified hours for an organization. */
    public function verifiedTotalForOrg(int $orgId): float
    {
        $st = $this->db->prepare(
            'SELECT COALESCE(SUM(ah.hours_verified),0)
             FROM attendance_and_hours ah
             JOIN registrations r ON r.id = ah.registration_id
             JOIN opportunities o ON o.id = r.opportunity_id
             WHERE o.organization_id = :oid AND r.status = \'registered\' AND ah.verification_status = \'verified\''
        );
        $st->execute(['oid' => $orgId]);
        return (float) $st->fetchColumn();
    }

    /**
     * Official participation report rows for one organization. Verified only.
     * @return array<int,array<string,mixed>>
     */
    public function reportRows(int $organizationId, ?string $from = null, ?string $to = null): array
    {
        $sql = 'SELECT o.title, o.start_at, u.full_name AS volunteer, u.email,
                       ah.attendance_status, ah.hours_verified, ah.verified_at
                FROM attendance_and_hours ah
                JOIN registrations r ON r.id = ah.registration_id
                JOIN opportunities o ON o.id = r.opportunity_id
                JOIN users u ON u.id = r.volunteer_id
                WHERE o.organization_id = :oid
                  AND r.status = \'registered\'
                  AND ah.verification_status = \'verified\'';
        $params = ['oid' => $organizationId];
        if ($from !== null && $from !== '') { $sql .= ' AND o.start_at >= :from'; $params['from'] = $from; }
        if ($to !== null && $to !== '')     { $sql .= ' AND o.start_at <= :to';   $params['to'] = $to; }
        $sql .= ' ORDER BY o.start_at DESC, u.full_name';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }
}
