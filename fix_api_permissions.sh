#!/bin/bash

# Script to fix API permissions and configuration on server

echo "=== Fixing API Permissions ==="

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 755 public

# Set ownership (adjust www-data to your web server user)
echo "Setting ownership..."
chown -R www-data:www-data storage bootstrap/cache
chown -R www-data:www-data public

# Clear Laravel cache
echo "Clearing Laravel cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize Laravel
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test API endpoint
echo "Testing API endpoint..."
curl -X GET "http://localhost/api/v1/cities" -H "Accept: application/json"

echo ""
echo "=== Done ==="
echo "Please check the output above to verify the API is working."
