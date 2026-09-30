<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Database\Connection;
use App\Http\Router;
use App\Repositories\CategoryRepository;
use App\View\SmartyView;

require dirname(__DIR__) . '/vendor/autoload.php';

$rootDirectory = dirname(__DIR__);
$view = createView($rootDirectory);
$router = createRouter($rootDirectory);
$homeController = createHomeController($rootDirectory, $view);
[$method, $path] = resolveRequest();

$content = renderRoute($router, $homeController, $view, $method, $path);

header('Content-Type: text/html; charset=utf-8');

echo $content;

/**
 * Создаёт сервис рендеринга шаблонов
 *
 * @param string $rootDirectory Корневой каталог проекта
 * @return SmartyView Сервис рендеринга шаблонов
 */
function createView(string $rootDirectory): SmartyView
{
    return new SmartyView(
        $rootDirectory . '/templates',
        $rootDirectory . '/var/cache/smarty/compile',
        $rootDirectory . '/var/cache/smarty/cache',
    );
}

/**
 * Создаёт маршрутизатор из конфигурации веб-маршрутов
 *
 * @param string $rootDirectory Корневой каталог проекта
 * @return Router Маршрутизатор HTTP-запросов
 */
function createRouter(string $rootDirectory): Router
{
    $routes = require $rootDirectory . '/routes/web.php';

    return new Router($routes);
}

/**
 * Создаёт контроллер главной страницы с его зависимостями
 *
 * @param string $rootDirectory Корневой каталог проекта
 * @param SmartyView $view Сервис рендеринга шаблонов
 * @return HomeController Контроллер главной страницы
 */
function createHomeController(string $rootDirectory, SmartyView $view): HomeController
{
    $databaseConfig = require $rootDirectory . '/config/database.php';
    $connection = Connection::create($databaseConfig);
    $categoryRepository = new CategoryRepository($connection);

    return new HomeController($categoryRepository, $view);
}

/**
 * Определяет HTTP-метод и путь текущего запроса
 *
 * @return array{string, string} HTTP-метод и путь запроса
 */
function resolveRequest(): array
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';

    if (!is_string($method) || !is_string($uri)) {
        throw new LogicException('Некорректные параметры HTTP-запроса');
    }

    $path = parse_url($uri, PHP_URL_PATH);

    return [$method, is_string($path) ? $path : '/'];
}

/**
 * Выполняет обработчик найденного маршрута
 *
 * @param Router $router Маршрутизатор HTTP-запросов
 * @param HomeController $homeController Контроллер главной страницы
 * @param SmartyView $view Сервис рендеринга шаблонов
 * @param string $method HTTP-метод запроса
 * @param string $path Путь запроса
 * @return string Содержимое HTTP-ответа
 */
function renderRoute(
    Router $router,
    HomeController $homeController,
    SmartyView $view,
    string $method,
    string $path,
): string {
    $route = $router->dispatch($method, $path);

    if ($route === null) {
        http_response_code(404);

        return $view->render('errors/404.tpl');
    }

    $slug = $route['parameters']['slug'] ?? '';

    return match ($route['name']) {
        'home' => $homeController->index(),
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
