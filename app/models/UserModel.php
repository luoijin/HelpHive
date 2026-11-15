<?php
declare(strict_types=1);

final class UserModel
{
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?array
    {
        $normalized = strtolower(trim($email));
        $st = $this->db->prepare('SELECT * FROM users WHERE LOWER(email) = :email LIMIT 1');
        $st->execute(['email' => $normalized]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(string $fullName, string $email, string $passwordHash, string $role = 'volunteer'): int
    {
        $normalized = strtolower(trim($email));
        $st = $this->db->prepare(
            'INSERT INTO users (full_name, email, password_hash, role) VALUES (:name, :email, :hash, :role)'
        );
        $st->execute([
            'name' => trim($fullName),
            'email' => $normalized,
            'hash' => $passwordHash,
            'role' => $role,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function all(): array
    {
        $st = $this->db->query('SELECT id, full_name, email, role, is_active, created_at FROM users ORDER BY id DESC');
        return $st->fetchAll();
    }

    public function toggleActive(int $id): bool
    {
        $st = $this->db->prepare('UPDATE users SET is_active = NOT is_active WHERE id = :id');
        $st->execute(['id' => $id]);
        return $st->rowCount() === 1;
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function updateRole(int $id, string $role): bool
    {
        $st = $this->db->prepare('UPDATE users SET role = :role WHERE id = :id');
        $st->execute(['role' => $role, 'id' => $id]);
        return $st->rowCount() === 1;
    }
}
