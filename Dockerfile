FROM php:8.2-apache

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    zip \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Enable Apache rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Set Apache document root to /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]