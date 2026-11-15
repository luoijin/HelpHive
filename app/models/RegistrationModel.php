<?php
declare(strict_types=1);

final class RegistrationModel
{
    public function __construct(private PDO $db) {}

    /**
     * Register a volunteer. Atomic: locks the opportunity row to prevent overbooking.
     * @return string 'ok' | 'closed' | 'deadline' | 'full' | 'duplicate'
     */
    public function register(int $opportunityId, int $volunteerId): string
    {
        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare(
                'SELECT capacity, registration_deadline, status FROM opportunities WHERE id = :id FOR UPDATE'
            );
            $st->execute(['id' => $opportunityId]);
            $opp = $st->fetch();

            if (!$opp || $opp['status'] !== 'published') { $this->db->rollBack(); return 'closed'; }
            if (new DateTime() > new DateTime($opp['registration_deadline'])) { $this->db->rollBack(); return 'deadline'; }

            $count = $this->db->prepare(
                'SELECT COUNT(*) FROM registrations WHERE opportunity_id = :id AND status = \'registered\''
            );
            $count->execute(['id' => $opportunityId]);
            if ((int) $count->fetchColumn() >= (int) $opp['capacity']) { $this->db->rollBack(); return 'full'; }

            // UNIQUE(opportunity_id, volunteer_id) is the final duplicate guard.
            $ins = $this->db->prepare(
                'INSERT INTO registrations (opportunity_id, volunteer_id) VALUES (:o, :v)'
            );
            $ins->execute(['o' => $opportunityId, 'v' => $volunteerId]);
            $regId = (int) $this->db->lastInsertId();

            $this->db->prepare('INSERT INTO attendance_and_hours (registration_id) VALUES (:r)')
                     ->execute(['r' => $regId]);

            $this->db->commit();
            return 'ok';
        } catch (PDOException $e) {
            $this->db->rollBack();
            if ($e->getCode() === '23000') { return 'duplicate'; }
            throw $e;
        }
    }

    public function forVolunteer(int $volunteerId): array
    {
        $sql = 'SELECT r.*, o.title, o.cause_area, o.location, o.start_at, o.end_at, org.name AS org_name
                FROM registrations r
                JOIN opportunities o ON o.id = r.opportunity_id
                JOIN organizations org ON org.id = o.organization_id
                WHERE r.volunteer_id = :vid
                ORDER BY r.registered_at DESC';
        $st = $this->db->prepare($sql);
        $st->execute(['vid' => $volunteerId]);
        return $st->fetchAll();
    }

    public function cancel(int $registrationId, int $volunteerId): bool
    {
        $st = $this->db->prepare(
            'UPDATE registrations r
             LEFT JOIN attendance_and_hours ah ON ah.registration_id = r.id
             SET r.status = \'cancelled\'
             WHERE r.id = :id AND r.volunteer_id = :vid
               AND (ah.verification_status IS NULL OR ah.verification_status <> \'verified\')'
        );
        $st->execute(['id' => $registrationId, 'vid' => $volunteerId]);
        return $st->rowCount() === 1;
    }

    public function attendanceHistoryForVolunteer(int $volunteerId): array
    {
        $sql = 'SELECT r.id AS registration_id, r.opportunity_id, o.title, o.start_at,
                       ah.attendance_status, ah.hours_claimed, ah.hours_verified, ah.verification_status, ah.verified_at
                FROM registrations r
                JOIN opportunities o ON o.id = r.opportunity_id
                LEFT JOIN attendance_and_hours ah ON ah.registration_id = r.id
                WHERE r.volunteer_id = :vid AND r.status = \'registered\'
                ORDER BY o.start_at DESC';
        $st = $this->db->prepare($sql);
        $st->execute(['vid' => $volunteerId]);
        return $st->fetchAll();
    }
}
