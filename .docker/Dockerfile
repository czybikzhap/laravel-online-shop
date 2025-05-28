FROM php:8.3.0-fpm

# Устанавливаем зависимости и расширения для PHP
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libpq-dev \
        libxpm-dev \
        libvpx-dev \
        libzip-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-install -j$(nproc) sockets \
    && docker-php-ext-enable sockets \
    && docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
        --with-xpm=/usr/lib/x86_64-linux-gnu/

# Устанавливаем cron и другие зависимости
RUN apt-get update && apt-get install -y \
    cron \
    nano \
    curl

# Устанавливаем Redis расширение
RUN pecl install redis && docker-php-ext-enable redis

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаем пользователя www
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Переключаемся на root для установки cron и копирования файлов
USER root

# Копируем cron-файл внутрь контейнера
COPY cron/laravel-schedule /etc/cron.d/laravel-schedule

# Устанавливаем права для cron-файла
RUN chmod 0644 /etc/cron.d/laravel-schedule

# Регистрируем cron задачу
RUN crontab /etc/cron.d/laravel-schedule

# Переключаемся обратно на пользователя www
USER www


