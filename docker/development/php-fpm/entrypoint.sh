#!/bin/sh
set -e

# Check if $UID and $GID are set, else fallback to default (1000:1000)
USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

# Fix file ownership and permissions using the passed UID and GID
echo "Fixing file permissions with UID=${USER_ID} and GID=${GROUP_ID}..."
# Only chown critical runtime directories to avoid failures on Windows bind mounts.
if [ -d /var/www/storage ]; then
  chown -R ${USER_ID}:${GROUP_ID} /var/www/storage 2>/dev/null || echo "Some files could not be changed in storage"
fi
if [ -d /var/www/bootstrap/cache ]; then
  chown -R ${USER_ID}:${GROUP_ID} /var/www/bootstrap/cache 2>/dev/null || echo "Some files could not be changed in bootstrap/cache"
fi

# Clear configurations to avoid caching issues in development
echo "Clearing configurations..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run the default command (e.g., php-fpm or bash)
exec "$@"
