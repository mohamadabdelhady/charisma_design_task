FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    zip

RUN docker-php-ext-install pdo pdo_pgsql
RUN a2enmod rewrite
RUN pecl install redis && docker-php-ext-enable redis

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

