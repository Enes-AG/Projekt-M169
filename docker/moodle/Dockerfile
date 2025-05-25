# Version 1.0 erstell am 24.05.2025 (Keine Änderfungen waren nötig)

FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    libjpeg-dev libpng-dev libfreetype6-dev libxml2-dev \
    mariadb-client unzip \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install mysqli gd xml \
    && a2enmod rewrite
