<?php
declare(strict_types=1);

/** Escape output for HTML (XSS protection). */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Return an absolute URL for a public asset path. */
function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

/** Render a partial view directly without layout wrapper. */
function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewPath = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
    if (!file_exists($viewPath)) {
        throw new RuntimeException("View partial not found: {$viewPath}");
    }
    require $viewPath;
}

/**
 * Render a full view within a layout wrapper (default 'layouts/main').
 */
function view(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data, EXTR_SKIP);
    $viewPath = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
    if (!file_exists($viewPath)) {
        throw new RuntimeException("View file not found: {$viewPath}");
    }

    ob_start();
    require $viewPath;
    $content = ob_get_clean();

    if ($layout === '' || $layout === null) {
        echo $content;
        return;
    }

    $layoutPath = BASE_PATH . '/app/views/' . str_replace('.', '/', $layout) . '.php';
    if (file_exists($layoutPath)) {
        require $layoutPath;
    } else {
        echo $content;
    }
}
