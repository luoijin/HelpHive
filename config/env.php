<?php
declare(strict_types=1);

/**
 * Minimal .env loader (no external dependencies).
 * @param string $path Absolute path to the .env file.
 */
function loadEnv(string $path): void
{
    if (!is_readable($path)) {
        return;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if ($line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $_ENV[$key] = $value;
    }
}

/** Read an environment value with a default. */
function env(string $key, ?string $default = null): ?string
{
    return $_ENV[$key] ?? $default;
}