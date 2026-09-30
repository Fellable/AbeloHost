<?php

declare(strict_types=1);

use App\Http\Router;
use App\View\SmartyView;

require dirname(__DIR__) . '/vendor/autoload.php';

$rootDirectory = dirname(__DIR__);
$view = new SmartyView(
    $rootDirectory . '/templates',
    $rootDirectory . '/var/cache/smarty/compile',
    $rootDirectory . '/var/cache/smarty/cache',
);

/** @var array<string, array<string, string>> $routes */
$routes = require $rootDirectory . '/routes/web.php';
$router = new Router($routes);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (!is_string($method) || !is_string($uri)) {
    throw new LogicException('Некорректные параметры HTTP-запроса');
}

$path = parse_url($uri, PHP_URL_PATH);

if (!is_string($path)) {
    $path = '/';
}

$route = $router->dispatch($method, $path);

if ($route === null) {
    http_response_code(404);
    $content = $view->render('errors/404.tpl');
} else {
    $slug = $route['parameters']['slug'] ?? '';
    $content = match ($route['name']) {
        'home' => 'Главная страница',
        'categories.show' => sprintf(
            'Категория: %s',
            htmlspecialchars($slug, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
        ),
        'posts.show' => sprintf(
            'Статья: %s',
            htmlspecialchars($slug, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
        ),
        default => throw new LogicException('Для маршрута не задан обработчик'),
    };
}

header('Content-Type: text/html; charset=utf-8');

echo $content;
