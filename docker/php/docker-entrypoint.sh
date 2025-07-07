#!/bin/bash

echo "==> Waiting for MySQL to be ready..."
dockerize -wait tcp://db:3306 -wait-retry-interval 5s -timeout 60s

echo "==> Starting Laravel setup..."

cd /var/www

# Install PHP dependencies
composer install --no-interaction --prefer-dist

# Generate app key if needed
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Publish Swagger config (only if not already published)
if [ ! -f "config/l5-swagger.php" ]; then
    echo "==> Publishing Swagger Config..."
    php artisan vendor:publish --provider="L5Swagger\\L5SwaggerServiceProvider" --tag="config"
    php artisan vendor:publish --provider="L5Swagger\\L5SwaggerServiceProvider" --tag="views"
fi

php artisan key:generate
php artisan config:clear
php artisan config:cache

# Install Sanctum if not already present
if ! php artisan migrate:status | grep -q "personal_access_tokens"; then
    echo "==> Publishing Sanctum migration..."
    php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
fi

# Wait until Laravel can connect to DB
until php artisan db:show > /dev/null 2>&1; do
    echo "==> Waiting for Laravel to connect to MySQL..."
    sleep 3
done

# Run migrations (forced, no prompt)
echo "==> Running database migrations..."
php artisan migrate --force

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Start PHP-FPM
exec php-fpm