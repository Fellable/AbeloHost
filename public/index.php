<?php

declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Database\Connection;
use App\Http\Router;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\View\SmartyView;

require dirname(__DIR__) . '/vendor/autoload.php';

$rootDirectory = dirname(__DIR__);
$view = createView($rootDirectory);
$router = createRouter($rootDirectory);
$connection = createConnection($rootDirectory);
$homeController = createHomeController($connection, $view);
$categoryController = createCategoryController($connection, $view);
$postController = createPostController($connection, $view);
[$method, $path] = resolveRequest();
$sort = resolveSort();
$page = resolvePage();

$content = renderRoute(
    $router,
    $homeController,
    $categoryController,
    $postController,
    $view,
    $sort,
    $page,
    $method,
    $path,
);

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
 * Создаёт подключение к базе данных
 *
 * @param string $rootDirectory Корневой каталог проекта
 * @return PDO Подключение к базе данных
 */
function createConnection(string $rootDirectory): PDO
{
    $databaseConfig = require $rootDirectory . '/config/database.php';

    return Connection::create($databaseConfig);
}

/**
 * Создаёт контроллер главной страницы
 *
 * @param PDO $connection Подключение к базе данных
 * @param SmartyView $view Сервис рендеринга шаблонов
 * @return HomeController Контроллер главной страницы
 */
function createHomeController(PDO $connection, SmartyView $view): HomeController
{
    return new HomeController(
        new CategoryRepository($connection),
        $view,
    );
}

/**
 * Создаёт контроллер страницы категории
 *
 * @param PDO $connection Подключение к базе данных
 * @param SmartyView $view Сервис рендеринга шаблонов
 * @return CategoryController Контроллер страницы категории
 */
function createCategoryController(PDO $connection, SmartyView $view): CategoryController
{
    return new CategoryController(
        new CategoryRepository($connection),
        new PostRepository($connection),
        $view,
    );
}

/**
 * Создаёт контроллер страницы статьи
 *
 * @param PDO $connection Подключение к базе данных
 * @param SmartyView $view Сервис рендеринга шаблонов
 * @return PostController Контроллер страницы статьи
 */
function createPostController(PDO $connection, SmartyView $view): PostController
{
    return new PostController(
        new PostRepository($connection),
        $view,
    );
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
 * Определяет выбранную сортировку статей
 *
 * @return string Запрошенная сортировка
 */
function resolveSort(): string
{
    $sort = $_GET['sort'] ?? 'date';

    return is_string($sort) ? $sort : 'date';
}

/**
 * Определяет номер текущей страницы
 *
 * @return int Номер страницы
 */
function resolvePage(): int
{
    $page = $_GET['page'] ?? '1';

    if (!is_string($page) || !ctype_digit($page)) {
        return 1;
    }

    return max(1, (int) $page);
}

/**
 * Выполняет обработчик найденного маршрута
 *
 * @param Router $router Маршрутизатор HTTP-запросов
 * @param HomeController $homeController Контроллер главной страницы
 * @param CategoryController $categoryController Контроллер страницы категории
 * @param PostController $postController Контроллер страницы статьи
 * @param SmartyView $view Сервис рендеринга шаблонов
 * @param string $sort Вариант сортировки статей
 * @param int $page Номер страницы
 * @param string $method HTTP-метод запроса
 * @param string $path Путь запроса
 * @return string Содержимое HTTP-ответа
 */
function renderRoute(
    Router $router,
    HomeController $homeController,
    CategoryController $categoryController,
    PostController $postController,
    SmartyView $view,
    string $sort,
    int $page,
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
        'categories.show' => $categoryController->show($slug, $sort, $page),
        'posts.show' => $postController->show($slug),

        default => throw new LogicException('Для маршрута не задан обработчик'),
    };
}
