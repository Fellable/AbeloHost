<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;

/**
 * Модель статьи блога
 */
final class Post
{
    /**
     * @param int $id Уникальный идентификатор статьи
     * @param string $title Название статьи
     * @param string $slug Уникальная строка для URL статьи
     * @param string $description Краткое описание статьи
     * @param string $content Полный текст статьи
     * @param Image $image Изображение статьи
     * @param int $views Количество просмотров статьи
     * @param DateTimeImmutable $publishedAt Дата и время публикации статьи
     * @param list<Category> $categories Категории, к которым относится статья
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $content,
        public readonly Image $image,
        public readonly int $views,
        public readonly DateTimeImmutable $publishedAt,
        public readonly array $categories,
    ) {
    }
}
