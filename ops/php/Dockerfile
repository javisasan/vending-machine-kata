# Composer stage
FROM composer as composer-stage

# PHP-FPM stage
FROM php:8.3-fpm as final

WORKDIR /app

COPY --from=composer-stage /usr/bin/composer /usr/bin/composer

COPY ../../ /app/

RUN apt update && apt install -y \
        git \
        zip

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN composer install \
    --ignore-platform-reqs \
    --no-ansi \
    --no-interaction
