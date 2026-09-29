<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Модель изображения статьи
 */
final class Image
{
    /**
     * @param int $id Уникальный идентификатор изображения
     * @param string $fullPath Путь или URL полноразмерного изображения
     * @param string $previewPath Путь или URL превью изображения
     * @param string $alt Альтернативный текст изображения
     */
    public function __construct(
        public readonly int $id,
        public readonly string $fullPath,
        public readonly string $previewPath,
        public readonly string $alt,
    ) {
    }
}