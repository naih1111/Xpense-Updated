FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    default-mysql-client \
    libzip-dev

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath gd intl pdo pdo_mysql zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . /var/www

# Copy .env.example to .env if .env doesn't exist
COPY .env.example .env

# Copy custom PHP configuration
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Install dependencies
RUN composer install --no-scripts

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Generate application key
RUN php artisan key:generate

# Cache configuration
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage
RUN chmod -R 777 /var/www/database

# Configure PHP-FPM
RUN echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf
RUN echo "pm.start_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf
RUN echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf
RUN echo "pm.max_spare_servers = 35" >> /usr/local/etc/php-fpm.d/www.conf