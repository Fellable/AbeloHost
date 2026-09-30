<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * Репозиторий статей блога
 */
final class PostRepository
{
    private const SORTING = [
        'date' => 'posts.published_at DESC, posts.id DESC',
        'views' => 'posts.views DESC, posts.id DESC',
    ];

    /**
     * @param PDO $connection Подключение к базе данных
     */
    public function __construct(
        private readonly PDO $connection,
    ) {
    }

    /**
     * Возвращает допустимый вариант сортировки
     *
     * @param string $sort Запрошенная сортировка
     * @return string Допустимая сортировка
     */
    public function normalizeSort(string $sort): string
    {
        return isset(self::SORTING[$sort]) ? $sort : 'date';
    }

    /**
     * Возвращает статьи категории с учётом сортировки
     *
     * @param int $categoryId Идентификатор категории
     * @param string $sort Вариант сортировки
     * @return array Список статей
     */
    public function findByCategoryId(int $categoryId, string $sort = 'date'): array
    {
        $sort = $this->normalizeSort($sort);
        $orderBy = self::SORTING[$sort];
        $statement = $this->connection->prepare(
            'SELECT
                posts.id,
                posts.title,
                posts.slug,
                posts.description,
                posts.views,
                posts.published_at,
                images.preview_path,
                images.alt AS image_alt
            FROM posts
            INNER JOIN category_post ON category_post.post_id = posts.id
            INNER JOIN images ON images.id = posts.image_id
            WHERE category_post.category_id = :category_id
            ORDER BY ' . $orderBy
        );
        $statement->execute(['category_id' => $categoryId]);

        return $statement->fetchAll();
    }
}
