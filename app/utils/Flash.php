<?php
declare(strict_types=1);

/**
 * Flash messages stored in session for one-time display.
 */
final class Flash
{
    public static function set(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }
        $_SESSION['flash'][$type][] = $message;
    }

    public static function success(string $message): void
    {
        self::set('success', $message);
    }

    public static function error(string $message): void
    {
        self::set('error', $message);
    }

    public static function info(string $message): void
    {
        self::set('info', $message);
    }

    /**
     * Retrieve and clear all flash messages.
     * @return array<string, list<string>>
     */
    public static function all(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return [];
        }
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $messages;
    }

    public static function has(string $type): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }
        return !empty($_SESSION['flash'][$type]);
    }

    /**
     * Retrieve and clear messages of a specific type.
     * @return list<string>
     */
    public static function get(string $type): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return [];
        }
        $messages = $_SESSION['flash'][$type] ?? [];
        unset($_SESSION['flash'][$type]);
        return $messages;
    }
}
