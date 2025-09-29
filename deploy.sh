#!/bin/bash

# CocoFarma Deployment Script
# Usage: ./deploy.sh [environment]

ENVIRONMENT=${1:-production}
echo "🚀 Deploying CocoFarma to $ENVIRONMENT environment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Please run this script from the Laravel project root directory"
    exit 1
fi

print_status "Setting up $ENVIRONMENT environment..."

# Copy environment file
if [ "$ENVIRONMENT" = "production" ]; then
    if [ -f ".env.production" ]; then
        cp .env.production .env
        print_status "Production environment file copied"
    else
        print_warning ".env.production not found, using existing .env"
    fi
fi

# Install dependencies
print_status "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Generate key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    print_status "Generating application key..."
    php artisan key:generate
fi

# Clear and cache config
print_status "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (optional, uncomment if needed)
# print_status "Running database migrations..."
# php artisan migrate --force

# Set proper permissions
print_status "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create storage link
php artisan storage:link

print_status "✅ Deployment completed successfully!"
print_status "Don't forget to:"
echo "  1. Upload files to your server"
echo "  2. Configure database settings in .env"
echo "  3. Run: php artisan migrate"
echo "  4. Set up web server (Apache/Nginx)"
echo "  5. Configure domain and SSL if needed"