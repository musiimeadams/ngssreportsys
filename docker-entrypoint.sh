#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e

# Cache configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations to set up database tables
echo "Running database migrations..."
php artisan migrate --force

# Seed default settings and admin account if needed
echo "Running database seeds..."
php artisan db:seed --force || true

# Start Apache in the foreground
echo "Starting Apache..."
exec apache2-foreground
