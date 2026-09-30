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
        'date' => 'posts.published_at',
        'views' => 'posts.views',
    ];

    private const DIRECTIONS = [
        'asc' => 'ASC',
        'desc' => 'DESC',
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
     * Возвращает допустимое направление сортировки
     *
     * @param string $direction Запрошенное направление
     * @return string Допустимое направление
     */
    public function normalizeDirection(string $direction): string
    {
        return isset(self::DIRECTIONS[$direction]) ? $direction : 'desc';
    }

    /**
     * Возвращает количество статей категории
     *
     * @param int $categoryId Идентификатор категории
     * @return int Количество статей
     */
    public function countByCategoryId(int $categoryId): int
    {
        $statement = $this->connection->prepare(
            'SELECT COUNT(*)
            FROM category_post
            WHERE category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    /**
     * Возвращает статьи категории с учётом сортировки
     *
     * @param int $categoryId Идентификатор категории
     * @param string $sort Вариант сортировки
     * @param string $direction Направление сортировки
     * @param int $limit Количество статей
     * @param int $offset Смещение выборки
     * @return array Список статей
     */
    public function findByCategoryId(
        int $categoryId,
        string $sort,
        string $direction,
        int $limit,
        int $offset,
    ): array {
        $sort = $this->normalizeSort($sort);
        $direction = $this->normalizeDirection($direction);
        $orderBy = self::SORTING[$sort] . ' ' . self::DIRECTIONS[$direction];
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
            ORDER BY ' . $orderBy . ', posts.id ' . self::DIRECTIONS[$direction] . '
            LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Увеличивает количество просмотров статьи
     *
     * @param int $postId Идентификатор статьи
     */
    public function incrementViews(int $postId): void
    {
        $statement = $this->connection->prepare(
            'UPDATE posts
            SET views = views + 1
            WHERE id = :id'
        );
        $statement->execute(['id' => $postId]);
    }

    /**
     * Возвращает статью по slug вместе с категориями
     *
     * @param string $slug Slug статьи
     * @return array|null Статья или null
     */
    public function findBySlug(string $slug): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT
                posts.id,
                posts.title,
                posts.slug,
                posts.description,
                posts.content,
                posts.views,
                posts.published_at,
                images.full_path,
                images.alt AS image_alt
            FROM posts
            INNER JOIN images ON images.id = posts.image_id
            WHERE posts.slug = :slug
            LIMIT 1'
        );
        $statement->execute(['slug' => $slug]);
        $post = $statement->fetch();

        if (!is_array($post)) {
            return null;
        }

        $categoryStatement = $this->connection->prepare(
            'SELECT categories.name, categories.slug
            FROM categories
            INNER JOIN category_post ON category_post.category_id = categories.id
            WHERE category_post.post_id = :post_id
            ORDER BY categories.name'
        );
        $categoryStatement->execute(['post_id' => $post['id']]);
        $post['categories'] = $categoryStatement->fetchAll();

        return $post;
    }

    /**
     * Возвращает три похожие статьи
     *
     * @param int $postId Идентификатор текущей статьи
     * @return array Список похожих статей
     */
    public function findRelated(int $postId): array
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
                images.alt AS image_alt,
                COUNT(*) AS shared_categories
            FROM category_post AS current_categories
            INNER JOIN category_post AS related_categories
                ON related_categories.category_id = current_categories.category_id
            INNER JOIN posts ON posts.id = related_categories.post_id
            INNER JOIN images ON images.id = posts.image_id
            WHERE current_categories.post_id = :post_id
                AND related_categories.post_id != current_categories.post_id
            GROUP BY posts.id, images.id
            ORDER BY shared_categories DESC, posts.published_at DESC, posts.id DESC
            LIMIT 3'
        );
        $statement->execute(['post_id' => $postId]);

        return $statement->fetchAll();
    }
}
