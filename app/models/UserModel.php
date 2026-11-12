<?php
declare(strict_types=1);

final class UserModel
{
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?array
    {
        $st = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $st->execute(['email' => $email]);
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
        $st = $this->db->prepare(
            'INSERT INTO users (full_name, email, password_hash, role) VALUES (:name, :email, :hash, :role)'
        );
        $st->execute([
            'name' => $fullName,
            'email' => $email,
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
}
