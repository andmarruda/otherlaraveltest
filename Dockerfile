# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt update --fix-missing && apt install -y \
    git \
    curl \
    sudo \
    nano \
    apt \
    wget \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    zip \
    libzip-dev \
    unzip \
    software-properties-common

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd mysqli && docker-php-ext-enable mysqli opcache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Get latest Composer Install composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# COPY ./docker-compose/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker-compose/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set the working directory to /var/www/html
WORKDIR /var/www

# Set the correct permissions for Laravel
# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

USER $user
