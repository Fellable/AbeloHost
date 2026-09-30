<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Пагинатор списка элементов
 */
final class Paginator
{
    public readonly int $currentPage;
    public readonly int $totalItems;
    public readonly int $perPage;
    public readonly int $totalPages;

    /**
     * @param int $currentPage Запрошенная страница
     * @param int $totalItems Общее количество элементов
     * @param int $perPage Количество элементов на странице
     */
    public function __construct(int $currentPage, int $totalItems, int $perPage)
    {
        $this->totalItems = max(0, $totalItems);
        $this->perPage = max(1, $perPage);
        $this->totalPages = max(1, (int)ceil($this->totalItems / $this->perPage));
        $this->currentPage = min(max(1, $currentPage), $this->totalPages);
    }
}
