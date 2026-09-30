<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\View\SmartyView;

/**
 * Контроллер главной страницы
 */
final class HomeController
{
    /**
     * @param CategoryRepository $categoryRepository Репозиторий категорий
     * @param SmartyView $view Сервис рендеринга шаблонов
     */
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly SmartyView $view,
    ) {
    }

    /**
     * Отображает категории с последними статьями
     *
     * @return string HTML главной страницы
     */
    public function index(): string
    {
        return $this->view->render(
            'home.tpl',
            ['categories' => $this->categoryRepository->findWithLatestPosts()],
        );
    }
}
