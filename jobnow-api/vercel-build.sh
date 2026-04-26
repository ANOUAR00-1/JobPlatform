#!/bin/bash

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Create necessary directories
mkdir -p /tmp/storage/framework/{sessions,views,cache}
mkdir -p /tmp/storage/logs

# Set permissions
chmod -R 775 /tmp/storage

echo "Build completed successfully!"
