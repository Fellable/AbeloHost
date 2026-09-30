<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * Репозиторий статей блога
 */
final class PostRepository
{
    /**
     * @param PDO $connection Подключение к базе данных
     */
    public function __construct(
        private readonly PDO $connection,
    ) {
    }

    /**
     * Возвращает статьи категории
     *
     * @param int $categoryId Идентификатор категории
     * @return array Список статей
     */
    public function findByCategoryId(int $categoryId): array
    {
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
            ORDER BY posts.published_at DESC, posts.id DESC'
        );
        $statement->execute(['category_id' => $categoryId]);

        return $statement->fetchAll();
    }
}
