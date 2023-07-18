FROM php:8.2-apache
MAINTAINER afup.org

RUN apt update && apt install -y \
    git \
    zip

# User creation
RUN usermod -u 1000 www-data \
 && groupmod -g 1000 www-data \
 && usermod -g 1000 www-data

# Apache Mod Rewrite
RUN a2enmod rewrite

# Composer install
RUN mkdir /var/www/.composer && chown www-data /var/www/.composer
COPY --from=composer /usr/bin/composer /usr/bin/composer