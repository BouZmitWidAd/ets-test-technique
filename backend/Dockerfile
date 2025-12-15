FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev \
    && docker-php-ext-install intl

# MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install

EXPOSE 8000