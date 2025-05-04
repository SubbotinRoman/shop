# Используем официальный образ PHP 8.2 с FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# Установка зависимостей, необходимых для Laravel и работы с MySQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm

# Установка расширений PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Установка Composer (менеджер пакетов PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка прав для пользователя www-data (под которым работает PHP-FPM)
RUN chown -R www-data:www-data /var/www/html

# Указываем пользователя по умолчанию
USER www-data

# Команда по умолчанию при запуске контейнера (запуск PHP-FPM)
CMD ["php-fpm"]