<?php
declare(strict_types=1);

final class OrganizationModel
{
    public function __construct(private PDO $db) {}

    public function idForUser(int $userId): int
    {
        $st = $this->db->prepare('SELECT id FROM organizations WHERE user_id = :uid LIMIT 1');
        $st->execute(['uid' => $userId]);
        $id = $st->fetchColumn();
        if ($id === false) {
            throw new RuntimeException("No organization profile found for user ID {$userId}");
        }
        return (int) $id;
    }

    public function findByUserId(int $userId): ?array
    {
        $st = $this->db->prepare('SELECT * FROM organizations WHERE user_id = :uid LIMIT 1');
        $st->execute(['uid' => $userId]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM organizations WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(int $userId, string $name, ?string $description = null, ?string $contactEmail = null, ?string $location = null, int $isApproved = 0): int
    {
        $st = $this->db->prepare(
            'INSERT INTO organizations (user_id, name, description, contact_email, location, is_approved)
             VALUES (:uid, :name, :desc, :email, :loc, :appr)'
        );
        $st->execute([
            'uid' => $userId,
            'name' => $name,
            'desc' => $description,
            'email' => $contactEmail,
            'loc' => $location,
            'appr' => $isApproved,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, ?string $description, ?string $contactEmail, ?string $location): bool
    {
        $st = $this->db->prepare(
            'UPDATE organizations SET name = :name, description = :desc, contact_email = :email, location = :loc WHERE id = :id'
        );
        $st->execute([
            'name' => $name,
            'desc' => $description,
            'email' => $contactEmail,
            'loc' => $location,
            'id' => $id,
        ]);
        return $st->rowCount() >= 0;
    }

    public function all(): array
    {
        $st = $this->db->query('SELECT * FROM organizations ORDER BY id DESC');
        return $st->fetchAll();
    }

    public function approve(int $id): bool
    {
        $st = $this->db->prepare('UPDATE organizations SET is_approved = 1 WHERE id = :id');
        $st->execute(['id' => $id]);
        return $st->rowCount() === 1;
    }

    public function reject(int $id): bool
    {
        $st = $this->db->prepare('UPDATE organizations SET is_approved = 0 WHERE id = :id');
        $st->execute(['id' => $id]);
        return $st->rowCount() === 1;
    }

    public function pendingCount(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM organizations WHERE is_approved = 0')->fetchColumn();
    }
}
