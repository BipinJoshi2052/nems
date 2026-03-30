FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    icu-dev libzip-dev oniguruma-dev postgresql-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    linux-headers $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql zip gd opcache pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
