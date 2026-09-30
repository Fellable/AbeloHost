FROM composer:2.8 AS composer

FROM php:8.3-fpm-alpine

RUN docker-php-ext-install -j"$(nproc)" pdo_mysql opcache

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini

WORKDIR /var/www/html

CMD ["php-fpm"]

