<?php
declare(strict_types=1);

final class OpportunityModel
{
    public function __construct(private PDO $db) {}

    public function allPublished(?string $keyword = null, ?string $cause = null, ?string $location = null): array
    {
        $sql = 'SELECT o.*, org.name AS org_name
                FROM opportunities o
                JOIN organizations org ON org.id = o.organization_id
                WHERE o.status = \'published\'';
        $params = [];

        if (!empty($keyword)) {
            $sql .= ' AND (o.title LIKE :kw OR o.description LIKE :kw)';
            $params['kw'] = '%' . $keyword . '%';
        }
        if (!empty($cause)) {
            $sql .= ' AND o.cause_area = :cause';
            $params['cause'] = $cause;
        }
        if (!empty($location) && trim($location) !== '') {
            $sql .= ' AND o.location LIKE :loc';
            $params['loc'] = '%' . trim($location) . '%';
        }

        $sql .= ' ORDER BY o.start_at ASC';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function findPublished(int $id): ?array
    {
        $st = $this->db->prepare(
            'SELECT o.*, org.name AS org_name, org.contact_email AS org_email
             FROM opportunities o
             JOIN organizations org ON org.id = o.organization_id
             WHERE o.id = :id AND o.status = \'published\' LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare(
            'SELECT o.*, org.name AS org_name, org.contact_email AS org_email
             FROM opportunities o
             JOIN organizations org ON org.id = o.organization_id
             WHERE o.id = :id LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function forOrganization(int $orgId): array
    {
        $st = $this->db->prepare('SELECT * FROM opportunities WHERE organization_id = :oid ORDER BY id DESC');
        $st->execute(['oid' => $orgId]);
        return $st->fetchAll();
    }

    public function create(array $data): int
    {
        $st = $this->db->prepare(
            'INSERT INTO opportunities (organization_id, title, description, cause_area, location, start_at, end_at, registration_deadline, capacity, requirements, status)
             VALUES (:oid, :title, :desc, :cause, :loc, :start, :end, :deadline, :cap, :req, :status)'
        );
        $st->execute([
            'oid' => $data['organization_id'],
            'title' => $data['title'],
            'desc' => $data['description'],
            'cause' => $data['cause_area'],
            'loc' => $data['location'],
            'start' => $data['start_at'],
            'end' => $data['end_at'],
            'deadline' => $data['registration_deadline'],
            'cap' => $data['capacity'],
            'req' => $data['requirements'] ?? null,
            'status' => $data['status'] ?? 'draft',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $orgId, array $data): bool
    {
        $st = $this->db->prepare(
            'UPDATE opportunities SET title = :title, description = :desc, cause_area = :cause, location = :loc,
                    start_at = :start, end_at = :end, registration_deadline = :deadline, capacity = :cap,
                    requirements = :req, status = :status
             WHERE id = :id AND organization_id = :oid'
        );
        $st->execute([
            'title' => $data['title'],
            'desc' => $data['description'],
            'cause' => $data['cause_area'],
            'loc' => $data['location'],
            'start' => $data['start_at'],
            'end' => $data['end_at'],
            'deadline' => $data['registration_deadline'],
            'cap' => $data['capacity'],
            'req' => $data['requirements'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'id' => $id,
            'oid' => $orgId,
        ]);
        return $st->rowCount() >= 0;
    }

    public function setStatus(int $id, int $orgId, string $status): bool
    {
        $st = $this->db->prepare('UPDATE opportunities SET status = :status WHERE id = :id AND organization_id = :oid');
        $st->execute(['status' => $status, 'id' => $id, 'oid' => $orgId]);
        return $st->rowCount() === 1;
    }

    public function deleteDraft(int $id, int $orgId): bool
    {
        $st = $this->db->prepare('DELETE FROM opportunities WHERE id = :id AND organization_id = :oid AND status = \'draft\'');
        $st->execute(['id' => $id, 'oid' => $orgId]);
        return $st->rowCount() === 1;
    }

    public function registrationsWithAttendance(int $oppId, int $orgId): array
    {
        $sql = 'SELECT r.id AS registration_id, r.status AS registration_status, u.full_name AS volunteer_name, u.email,
                       ah.attendance_status, ah.hours_claimed, ah.hours_verified, ah.verification_status, ah.verified_at
                FROM registrations r
                JOIN opportunities o ON o.id = r.opportunity_id
                JOIN users u ON u.id = r.volunteer_id
                LEFT JOIN attendance_and_hours ah ON ah.registration_id = r.id
                WHERE o.id = :opp_id AND o.organization_id = :oid
                ORDER BY r.registered_at ASC';
        $st = $this->db->prepare($sql);
        $st->execute(['opp_id' => $oppId, 'oid' => $orgId]);
        return $st->fetchAll();
    }
}
