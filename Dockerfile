FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Increase PHP memory limit
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Copy application files
COPY . /var/www/html/

# Install PHP dependencies with increased memory
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Create .env file
RUN echo "APP_NAME=Laravel\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_URL=http://localhost\nDB_CONNECTION=sqlite\nDB_DATABASE=/var/www/html/database/database.sqlite\nCACHE_DRIVER=file\nSESSION_DRIVER=file\nQUEUE_CONNECTION=sync" > .env

# Generate application key
RUN php artisan key:generate --ansi

# Create database file
RUN touch database/database.sqlite

# Run migrations and seed
RUN php artisan migrate --force
RUN php artisan db:seed --force
RUN php artisan storage:link

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache configuration
RUN echo "<VirtualHost *:80>\n\tDocumentRoot /var/www/html/public\n\t<Directory /var/www/html/public>\n\t\tAllowOverride All\n\t\tRequire all granted\n\t</Directory>\n\tErrorLog \${APACHE_LOG_DIR}/error.log\n\tCustomLog \${APACHE_LOG_DIR}/access.log combined\n</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
