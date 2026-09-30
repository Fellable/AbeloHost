<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * Репозиторий категорий блога
 */
final class CategoryRepository
{
    /**
     * @param PDO $connection Подключение к базе данных
     */
    public function __construct(
        private readonly PDO $connection,
    ) {
    }

    /**
     * Возвращает категории с тремя последними статьями
     *
     * @return array Категории со статьями
     */
    public function findWithLatestPosts(): array
    {
        $statement = $this->connection->query(
            'SELECT
                categories.id AS category_id,
                categories.name AS category_name,
                categories.slug AS category_slug,
                categories.description AS category_description,
                ranked_posts.post_id,
                ranked_posts.post_title,
                ranked_posts.post_slug,
                ranked_posts.post_description,
                ranked_posts.preview_path,
                ranked_posts.image_alt,
                ranked_posts.views,
                ranked_posts.published_at
            FROM categories
            INNER JOIN (
                SELECT
                    category_post.category_id,
                    posts.id AS post_id,
                    posts.title AS post_title,
                    posts.slug AS post_slug,
                    posts.description AS post_description,
                    images.preview_path,
                    images.alt AS image_alt,
                    posts.views,
                    posts.published_at,
                    ROW_NUMBER() OVER (
                        PARTITION BY category_post.category_id
                        ORDER BY posts.published_at DESC, posts.id DESC
                    ) AS post_position
                FROM category_post
                INNER JOIN posts ON posts.id = category_post.post_id
                INNER JOIN images ON images.id = posts.image_id
            ) AS ranked_posts ON ranked_posts.category_id = categories.id
                AND ranked_posts.post_position <= 3
            ORDER BY categories.id, ranked_posts.published_at DESC, ranked_posts.post_id DESC'
        );

        if ($statement === false) {
            return [];
        }

        $rows = $statement->fetchAll();

        $categories = [];

        foreach ($rows as $row) {
            $categoryId = $row['category_id'];

            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $row['category_name'],
                    'slug' => $row['category_slug'],
                    'description' => $row['category_description'],
                    'posts' => [],
                ];
            }

            $categories[$categoryId]['posts'][] = [
                'id' => $row['post_id'],
                'title' => $row['post_title'],
                'slug' => $row['post_slug'],
                'description' => $row['post_description'],
                'preview_path' => $row['preview_path'],
                'image_alt' => $row['image_alt'],
                'views' => $row['views'],
                'published_at' => $row['published_at'],
            ];
        }

        return array_values($categories);
    }
}
