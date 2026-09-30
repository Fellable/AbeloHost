<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Направление сортировки
 */
enum SortDirection: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}