#!/bin/sh

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Ждем пока MySQL поднимется
echo "Waiting for MySQL..."

until mysql -h"db" -ularavel -psecret -e 'SELECT 1'; do
  echo "Waiting for MySQL to be ready..."
  sleep 3
done

echo "MySQL is up. Running migrations and seeders..."

php artisan migrate --force
php artisan db:seed --force

exec "$@"
