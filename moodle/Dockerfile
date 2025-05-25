# Version 1.0 nichts wurde verändert. 

FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    git \
    wget \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    tar \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip gd xml intl

RUN a2enmod rewrite

WORKDIR /var/www/html

# Moodle 4.5.2 herunterladen und entpacken
RUN wget https://codeload.github.com/moodle/moodle/tar.gz/refs/tags/v4.5.2 -O moodle-4.5.2.tar.gz && \
    tar -xzf moodle-4.5.2.tar.gz && \
    rm moodle-4.5.2.tar.gz && \
    mv moodle-4.5.2/* ./ && \
    rm -rf moodle-4.5.2

RUN chown -R www-data:www-data /var/www/html
