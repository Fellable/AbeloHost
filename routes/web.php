<?php

declare(strict_types=1);

return [
    'GET' => [
        '/' => 'home',
        '/categories/{slug}' => 'categories.show',
        '/posts/{slug}' => 'posts.show',
    ],
];
