FROM php:8.2-apache-buster
MAINTAINER afup.org

RUN apt update && apt-get install -y \
    git \
    zip

# User creation
RUN usermod -u 1000 www-data \
 && groupmod -g 1000 www-data \
 && usermod -g 1000 www-data

RUN docker-php-ext-install -j$(nproc) pdo_mysql

# Apache Mod Rewrite
RUN a2enmod rewrite

# Composer install
RUN mkdir /var/www/.composer && chown www-data /var/www/.composer
COPY --from=composer /usr/bin/composer /usr/bin/composer