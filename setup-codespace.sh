#!/bin/bash

echo "🚀 Setting up CocoFarma in GitHub Codespace..."

# Copy environment file
cp .env.codespace .env

# Generate application key
php artisan key:generate

# Install dependencies
composer install --no-interaction

# Install npm dependencies
npm install

# Setup database (using SQLite for simplicity in codespace)
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Build assets
npm run build

echo "✅ Setup completed!"
echo ""
echo "🎯 To run the application:"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "🌐 Your app will be available at the forwarded port 8000"
echo "   (Check the 'Ports' tab in VS Code terminal area)"