# Use the official PHP image with Apache and PHP 8.2
FROM php:8.2-apache

# Install system dependencies for PHP extensions and PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libjpeg-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libcap2-bin \
    # Install PHP extensions required for Laravel with PostgreSQL
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    # Clear cache
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory - this should match your volume mount in docker-compose
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Adjust Apache Document Root to public directory of Laravel
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Allow .htaccess with RewriteEngine
RUN a2dissite 000-default && a2enmod rewrite && a2enmod headers && a2ensite 000-default

# Enable PHP to bind to privileged ports
RUN setcap "cap_net_bind_service=+ep" /usr/local/bin/php

# Add user for application
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# Change ownership to www-data for all application files
RUN chown -R www-data:www-data /var/www/html

# Install composer dependencies
RUN composer install

# Fix permissions for Laravel
RUN chmod -R 777 storage && \
    chmod -R 777 bootstrap/cache

# Expose port 80
EXPOSE 80

# Command to run Apache server in the foreground
CMD ["apache2-foreground"]
