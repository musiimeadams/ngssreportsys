#!/bin/sh
set -e

echo "==> Caching Laravel configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Seeding default admin and school settings..."
php artisan db:seed --force || true

echo "==> Starting Apache..."
exec apache2-foreground
