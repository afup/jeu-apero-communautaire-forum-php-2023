FROM php:8.2-apache-buster
MAINTAINER afup.org

RUN apt-get update && apt-get install -y \
    git \
    libicu-dev \
    zip

# User creation
RUN usermod -u 1000 www-data \
 && groupmod -g 1000 www-data \
 && usermod -g 1000 www-data

# Extensions
RUN docker-php-ext-install -j$(nproc) pdo_mysql \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl

# Apache Mod Rewrite
RUN a2enmod rewrite

# Set Timezone
RUN printf '[PHP]\ndate.timezone = "Europe/Paris"\n' > /usr/local/etc/php/conf.d/tzone.ini

# Composer install
RUN mkdir /var/www/.composer && chown www-data /var/www/.composer
COPY --from=composer /usr/bin/composer /usr/bin/composer