<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\View\SmartyView;

/**
 * Контроллер страницы категории
 */
final class CategoryController
{
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
     * @return string HTML страницы категории
     */
    public function show(string $slug): string
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            http_response_code(404);

            return $this->view->render('errors/404.tpl');
        }

        $posts = $this->postRepository->findByCategoryId($category['id']);

        return $this->view->render(
            'category.tpl',
            [
                'category' => $category,
                'posts' => $posts,
            ],
        );
    }
}
