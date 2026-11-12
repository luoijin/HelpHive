<?php
declare(strict_types=1);

/** CSRF token generation and verification. */
final class Csrf
{
    /** @return string Token for the current session. */
    public static function token(): string
    {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    /** Hidden input for forms. */
    public static function field(): string
    {
        return '<input type="hidden" name="csrf" value="' . e(self::token()) . '">';
    }

    /** Abort with 419 if the POST token is missing or invalid. */
    public static function verify(): void
    {
        $sent = $_POST['csrf'] ?? '';
        if (!is_string($sent) || !hash_equals(self::token(), $sent)) {
            http_response_code(419);
            exit('Invalid CSRF token.');
        }
    }
}