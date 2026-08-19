<?php

declare(strict_types=1);

use App\Interfaces\ProduitRepositoryInterface;
use App\Repositories\ProduitRepository;
use Core\Container;
use Core\Database;
use Core\Router;
use Core\View;

session_start();

require __DIR__ . '/../vendor/autoload.php';

define('VIEW_PATH', dirname(__DIR__) . '/views');

if (!function_exists('flash')) {
    function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}

$container = new Container();
$container->bind(ProduitRepositoryInterface::class, fn () => new ProduitRepository(Database::connect()));

$router = new Router($container);
require __DIR__ . '/../routes/web.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = View::baseUrl();

if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}
if ($path === '') {
    $path = '/';
}

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
} catch (\Throwable $e) {
    http_response_code(500);
    echo View::render('errors/500', [
        'title'   => 'Erreur serveur',
        'message' => $e->getMessage(),
    ], null);
}
