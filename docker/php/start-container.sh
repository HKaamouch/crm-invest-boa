#!/bin/bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

echo "Container role: $role"
echo "Environment: $env"

if [ "$role" = "app" ]; then
    echo "Running the app..."

    # Wait for database to be ready
    until php artisan migrate:status > /dev/null 2>&1; do
        echo "Waiting for database connection..."
        sleep 2
    done

    # Run migrations if in production and not already run
    if [ "$env" = "production" ]; then
        echo "Checking for pending migrations..."
        php artisan migrate --force --no-interaction
    fi

    # Clear and cache config for performance
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Start PHP-FPM
    exec php-fpm

elif [ "$role" = "queue" ]; then
    echo "Running the queue worker..."
    php artisan queue:work --verbose --tries=3 --timeout=90 --memory=512

elif [ "$role" = "horizon" ]; then
    echo "Running Horizon..."
    php artisan horizon

elif [ "$role" = "scheduler" ]; then
    echo "Running the scheduler..."
    exec /usr/local/bin/start-scheduler.sh

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
