#!/bin/bash

# Install Composer if not available
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

# Install dependencies
echo "Installing Composer dependencies..."
cd /vercel/path0/jobnow-api || cd .
composer install --optimize-autoloader --no-dev --prefer-dist --no-interaction

echo "Build complete!"
