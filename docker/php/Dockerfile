FROM composer:2.8 AS composer

FROM php:8.3-fpm-alpine

RUN apk add --no-cache fcgi \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql opcache \
    && wget -qO /usr/local/bin/php-fpm-healthcheck \
        https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/v0.5.0/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php/php-fpm-healthcheck.conf /usr/local/etc/php-fpm.d/zz-healthcheck.conf

WORKDIR /var/www/html

CMD ["php-fpm"]

