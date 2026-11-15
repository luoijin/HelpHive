<?php
declare(strict_types=1);

final class SavedOpportunityModel
{
    public function __construct(private PDO $db) {}

    /**
     * Save/bookmark an opportunity for a user.
     */
    public function save(int $userId, int $opportunityId): bool
    {
        $st = $this->db->prepare(
            'INSERT IGNORE INTO saved_opportunities (user_id, opportunity_id) VALUES (:u, :o)'
        );
        $st->execute(['u' => $userId, 'o' => $opportunityId]);
        return $st->rowCount() >= 0;
    }

    /**
     * Remove a saved/bookmarked opportunity for a user.
     */
    public function unsave(int $userId, int $opportunityId): bool
    {
        $st = $this->db->prepare(
            'DELETE FROM saved_opportunities WHERE user_id = :u AND opportunity_id = :o'
        );
        $st->execute(['u' => $userId, 'o' => $opportunityId]);
        return $st->rowCount() >= 0;
    }

    /**
     * Check if an opportunity is saved by a user.
     */
    public function isSaved(int $userId, int $opportunityId): bool
    {
        $st = $this->db->prepare(
            'SELECT 1 FROM saved_opportunities WHERE user_id = :u AND opportunity_id = :o LIMIT 1'
        );
        $st->execute(['u' => $userId, 'o' => $opportunityId]);
        return (bool) $st->fetchColumn();
    }

    /**
     * Get all opportunity IDs saved by a user.
     * @return int[]
     */
    public function savedIdsForUser(int $userId): array
    {
        $st = $this->db->prepare(
            'SELECT opportunity_id FROM saved_opportunities WHERE user_id = :u'
        );
        $st->execute(['u' => $userId]);
        return array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Get all published opportunities saved by a user.
     */
    public function allForUser(int $userId): array
    {
        $sql = 'SELECT o.*, org.name AS org_name, org.contact_email AS org_email, so.created_at AS saved_at
                FROM saved_opportunities so
                JOIN opportunities o ON o.id = so.opportunity_id
                JOIN organizations org ON org.id = o.organization_id
                WHERE so.user_id = :u AND o.status = \'published\'
                ORDER BY so.created_at DESC';
        $st = $this->db->prepare($sql);
        $st->execute(['u' => $userId]);
        return $st->fetchAll();
    }
}
