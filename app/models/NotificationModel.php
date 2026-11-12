<?php
declare(strict_types=1);

final class NotificationModel
{
    public function __construct(private PDO $db) {}

    public function forUser(int $userId): array
    {
        $st = $this->db->prepare('SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC');
        $st->execute(['uid' => $userId]);
        return $st->fetchAll();
    }

    public function markAsRead(int $id, int $userId): bool
    {
        $st = $this->db->prepare('UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :uid');
        $st->execute(['id' => $id, 'uid' => $userId]);
        return $st->rowCount() === 1;
    }

    public function markAllAsRead(int $userId): bool
    {
        $st = $this->db->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = :uid');
        $st->execute(['uid' => $userId]);
        return $st->rowCount() >= 0;
    }

    public function notify(int $userId, string $type, string $message, ?string $link = null): int
    {
        $st = $this->db->prepare(
            'INSERT INTO notifications (user_id, type, message, link) VALUES (:uid, :type, :msg, :link)'
        );
        $st->execute([
            'uid' => $userId,
            'type' => $type,
            'msg' => $message,
            'link' => $link,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
