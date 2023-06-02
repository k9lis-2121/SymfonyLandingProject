FROM php:8-fpm

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

# Установка необходимых расширений PHP
RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        unzip \
    && docker-php-ext-install pdo_mysql zip

# Копирование настроек PHP
COPY ./php/local.ini /usr/local/etc/php/conf.d/local.ini

# Установка рабочей директории
WORKDIR /var/www/html

# Запуск веб-сервера PHP
CMD ["php", "-S", "0.0.0.0:9000", "-t", "/var/www/html/public"]

# Экспонирование порта 9000
EXPOSE 9000
