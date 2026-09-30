<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\PostSort;
use App\Enums\SortDirection;
use App\Services\CategoryService;
use App\View\SmartyView;

/**
 * Контроллер страницы категории
 */
final class CategoryController
{
    /**
     * @param CategoryService $categoryService Сервис страницы категории
     * @param SmartyView $view Сервис рендеринга шаблонов
     */
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly SmartyView $view,
    ) {
    }

    /**
     * Отображает категорию и список её статей
     *
     * @param string $slug Slug категории
     * @param PostSort $sort Поле сортировки
     * @param SortDirection $direction Направление сортировки
     * @param int $page Номер страницы
     * @return string HTML страницы категории
     */
    public function show(
        string $slug,
        PostSort $sort = PostSort::Date,
        SortDirection $direction = SortDirection::Desc,
        int $page = 1,
    ): string {
        $data = $this->categoryService->getPageData(
            $slug,
            $sort,
            $direction,
            $page,
        );

        if ($data === null) {
            http_response_code(404);

            return $this->view->render('errors/404.tpl');
        }

        return $this->view->render('category.tpl', $data);
    }
}