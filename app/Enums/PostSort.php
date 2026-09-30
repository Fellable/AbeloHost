<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Поле сортировки статей
 */
enum PostSort: string
{
    case Date = 'date';
    case Views = 'views';
}