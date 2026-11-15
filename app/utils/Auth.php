<?php
declare(strict_types=1);

/**
 * Session authentication and role-based access control.
 */
final class Auth
{
    public const VOLUNTEER = 'volunteer';
    public const ORGANIZATION = 'organization';
    public const ADMIN = 'admin';

    /** Start a hardened session. Call once in the front controller. */
    public static function boot(): void
    {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
        session_start();
        self::checkSessionUser();
    }

    /**
     * Re-verify session user against database: terminate session if deactivated
     * or deleted, and sync current role and name (G-23).
     */
    public static function checkSessionUser(): void
    {
        if (empty($_SESSION['user']['id'])) {
            return;
        }

        try {
            $userModel = new UserModel(Database::connection());
            $user = $userModel->findById((int) $_SESSION['user']['id']);
            if ($user === null || empty($user['is_active'])) {
                self::logout();
                return;
            }

            $_SESSION['user']['name'] = $user['full_name'];
            $_SESSION['user']['role'] = $user['role'];
        } catch (Throwable) {
            // If database is not ready or during CLI bootstrapping, keep existing session
        }
    }

    /**
     * Log a user in after password_verify() succeeds.
     * @param array $user Row from users table.
     */
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['full_name'],
            'role' => $user['role'],
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /** @return array|null Current user session data. */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    /** Require any authenticated user; redirect guests to /login. */
    public static function requireLogin(): array
    {
        self::checkSessionUser();
        $user = self::user();
        if ($user === null) {
            redirect('/login');
        }
        return $user;
    }

    /**
     * Require one of the given roles; otherwise 403.
     * @param string ...$roles Allowed roles.
     */
    public static function requireRole(string ...$roles): array
    {
        $user = self::requireLogin();
        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            exit('Forbidden');
        }
        return $user;
    }
}