<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Support\Paginator;
use App\View\SmartyView;

/**
 * Контроллер страницы категории
 */
final class CategoryController
{
    private const POSTS_PER_PAGE = 3;

    /**
     * @param CategoryRepository $categoryRepository Репозиторий категорий
     * @param PostRepository $postRepository Репозиторий статей
     * @param SmartyView $view Сервис рендеринга шаблонов
     */
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly PostRepository $postRepository,
        private readonly SmartyView $view,
    ) {
    }

    /**
     * Отображает категорию и список её статей
     *
     * @param string $slug Slug категории
     * @param string $sort Вариант сортировки статей
     * @param int $page Номер страницы
     * @return string HTML страницы категории
     */
    public function show(string $slug, string $sort = 'date', int $page = 1): string
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            http_response_code(404);

            return $this->view->render('errors/404.tpl');
        }

        $sort = $this->postRepository->normalizeSort($sort);
        $totalPosts = $this->postRepository->countByCategoryId($category['id']);
        $paginator = new Paginator($page, $totalPosts, self::POSTS_PER_PAGE);
        $offset = ($paginator->currentPage - 1) * $paginator->perPage;
        $posts = $this->postRepository->findByCategoryId(
            $category['id'],
            $sort,
            $paginator->perPage,
            $offset,
        );

        return $this->view->render(
            'category.tpl',
            [
                'category' => $category,
                'posts' => $posts,
                'sort' => $sort,
                'paginator' => $paginator,
            ],
        );
    }
}
