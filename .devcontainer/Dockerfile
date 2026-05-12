FROM php:7.4-apache

# Instala dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
        libpng-dev libjpeg-dev libzip-dev \
        zip unzip git \
    && docker-php-ext-install mysqli pdo pdo_mysql gd zip \
    && pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*