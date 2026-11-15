<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/config/env.php';
loadEnv(BASE_PATH . '/.env');

foreach (['utils/Database', 'utils/Auth', 'utils/Csrf', 'utils/helpers'] as $f) {
    require BASE_PATH . "/app/$f.php";
}
spl_autoload_register(function (string $class): void {
    foreach (['controllers', 'models', 'utils', 'utils/exporters'] as $dir) {
        $file = BASE_PATH . "/app/$dir/$class.php";
        if (is_file($file)) { require $file; return; }
    }
});
if (is_file(BASE_PATH . '/vendor/autoload.php')) { require BASE_PATH . '/vendor/autoload.php'; }

// Global Exception Handler (G-26)
set_exception_handler(function (Throwable $e): void {
    error_log((string) $e);
    http_response_code(500);
    $isLocal = strtolower((string) env('APP_ENV', 'local')) === 'local';
    $viewPath = BASE_PATH . '/app/views/errors/500.php';
    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        echo '<h1>500 Internal Server Error</h1>';
        if ($isLocal) {
            echo '<pre>' . htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8') . '</pre>';
        } else {
            echo '<p>An unexpected error occurred. Please try again later.</p>';
        }
    }
    exit;
});

Auth::boot();
$routes = require BASE_PATH . '/config/routes.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = '/' . trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

if ($method === 'POST') { Csrf::verify(); }

foreach ($routes as [$m, $pattern, $handler]) {
    $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>\d+)', $pattern) . '$#';
    if ($m === $method && preg_match($regex, $path, $matches)) {
        [$class, $action] = explode('@', $handler);
        $args = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        (new $class())->$action(...array_map('intval', $args));
        exit;
    }
}
http_response_code(404);
echo 'Not found';