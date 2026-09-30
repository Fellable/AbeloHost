<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Класс маршрутизации HTTP-запросов
 */
final class Router
{
    /**
     * @param array<string, array<string, string>> $routes Маршруты, сгруппированные по HTTP-методам
     */
    public function __construct(
        private readonly array $routes,
    )
    {
    }

    /**
     * Находит маршрут по HTTP-методу и пути
     *
     * @param string $method HTTP-метод запроса
     * @param string $path Путь запроса
     * @return array{name: string, parameters: array<string, string>}|null Найденный маршрут или null
     */
    public function dispatch(string $method, string $path): ?array
    {
        foreach ($this->routes[$method] ?? [] as $route => $name) {
            $pattern = str_replace(
                '\{slug\}',
                '(?P<slug>[^/]+)',
                preg_quote($route, '#'),
            );

            if (preg_match("#^{$pattern}$#", $path, $matches) !== 1) {
                continue;
            }

            $parameters = [];

            if (isset($matches['slug'])) {
                $parameters['slug'] = rawurldecode($matches['slug']);
            }

            return [
                'name' => $name,
                'parameters' => $parameters,
            ];
        }

        return null;
    }
}
