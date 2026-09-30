<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PostSort;
use App\Enums\SortDirection;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Support\Paginator;

/**
 * Сервис страницы категории
 */
final class CategoryService
{
    private const POSTS_PER_PAGE = 3;

    /**
     * @param CategoryRepository $categoryRepository Репозиторий категорий
     * @param PostRepository $postRepository Репозиторий статей
     */
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly PostRepository $postRepository,
    ) {
    }

    /**
     * Возвращает данные страницы категории
     *
     * @param string $slug Slug категории
     * @param PostSort $sort Поле сортировки
     * @param SortDirection $direction Направление сортировки
     * @param int $page Номер страницы
     * @return array|null Данные страницы или null
     */
    public function getPageData(
        string $slug,
        PostSort $sort,
        SortDirection $direction,
        int $page,
    ): ?array {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            return null;
        }

        $totalPosts = $this->postRepository->countByCategoryId($category['id']);
        $paginator = new Paginator($page, $totalPosts, self::POSTS_PER_PAGE);
        $offset = ($paginator->currentPage - 1) * $paginator->perPage;
        $posts = $this->postRepository->findByCategoryId(
            $category['id'],
            $sort,
            $direction,
            $paginator->perPage,
            $offset,
        );

        return [
            'category' => $category,
            'posts' => $posts,
            'sort' => $sort->value,
            'direction' => $direction->value,
            'paginator' => $paginator,
        ];
    }
}