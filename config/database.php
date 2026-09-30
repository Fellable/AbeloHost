<?php

declare(strict_types=1);

$port = getenv('DB_PORT');

return [
    'host' => getenv('DB_HOST') ?: 'mysql',
    'port' => is_numeric($port) ? (int)$port : 3306,
    'database' => getenv('DB_DATABASE') ?: 'abelohost_blog',
    'username' => getenv('DB_USERNAME') ?: 'abelohost_blog',
    'password' => getenv('DB_PASSWORD') ?: '',
];
