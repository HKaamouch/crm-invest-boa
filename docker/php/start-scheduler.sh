#!/bin/bash

echo "Starting Laravel Scheduler..."

# Run the scheduler every minute
while true; do
    php artisan schedule:run --verbose --no-interaction
    sleep 60
done
