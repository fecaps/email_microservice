# Stage 1 - Composer Dependencies
FROM composer:1.9 as composer

# Copy Laravel and Composer files used when installing Dependencies
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock

# Install PHP Dependencies
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# Stage 2 - Email microservice
FROM php:7.3-fpm

# Install Dependencies
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install pdo_mysql zip

# Set Workdir
WORKDIR /var/www/html/email_microservice

# Copy project files
COPY . /var/www/html/email_microservice

# Copy dependencies from Composer stage
COPY --from=composer ./app/vendor/ /var/www/html/email_microservice/vendor/
