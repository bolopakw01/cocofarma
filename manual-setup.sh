#!/bin/bash

echo "🔧 Manual Setup untuk GitHub Codespace..."

# Install PHP if not present
if ! command -v php &> /dev/null; then
    echo "Installing PHP 8.2..."
    sudo apt update
    sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-sqlite3 php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
fi

# Install Composer if not present
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

# Install Node.js if not present
if ! command -v node &> /dev/null; then
    echo "Installing Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt-get install -y nodejs
fi

# Setup Laravel project
echo "Setting up Laravel project..."
cp .env.codespace .env
php artisan key:generate

# Install PHP dependencies
composer install --no-interaction

# Install Node dependencies
npm install

# Setup database
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Build assets
npm run build

echo "✅ Setup completed!"
echo ""
echo "🚀 To run the application:"
echo "php artisan serve --host=0.0.0.0 --port=8000"