FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    ffmpeg \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP config for large video uploads
RUN echo "upload_max_filesize=100M\npost_max_size=110M\nmax_execution_time=300\nmemory_limit=512M" > /usr/local/etc/php/conf.d/uploads.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy the rest of the application (bust cache: v3)
COPY . .

# Ensure directories exist before composer scripts
RUN mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} storage/logs \
    && chmod -R 775 bootstrap/cache storage

# Re-run composer scripts after full copy
RUN composer dump-autoload --optimize

# Install npm dependencies and build frontend AFTER full copy
RUN npm ci && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Startup script
COPY <<'SCRIPT' /usr/local/bin/start.sh
#!/bin/bash
set -e

# Ensure storage directories exist at runtime
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache || true
php artisan migrate --force
php artisan db:seed --force || true
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
SCRIPT
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
