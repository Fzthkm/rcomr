FROM php:8.2-fpm

# Установим зависимости
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
