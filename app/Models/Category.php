<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Модель категории блога
 */
final class Category
{
    /**
     * @param int $id Уникальный идентификатор категории
     * @param string $name Название категории
     * @param string $slug Уникальная строка для URL категории
     * @param string $description Описание категории
     */
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
    )
    {
    }
}

