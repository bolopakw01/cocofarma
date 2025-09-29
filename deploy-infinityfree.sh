#!/bin/bash

echo "=== DEPLOYMENT SCRIPT UNTUK INFINITYFREE ==="
echo "Pastikan Anda sudah:"
echo "1. Upload dan extract files ke htdocs/"
echo "2. Setup database MySQL"
echo "3. Rename .env.infinityfree menjadi .env"
echo ""

# Set permissions
echo "Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set storage link
echo "Creating storage link..."
php artisan storage:link

echo ""
echo "=== DEPLOYMENT SELESAI ==="
echo "Aplikasi siap digunakan di: https://yourdomain.epizy.com"
echo ""
echo "Catatan penting:"
echo "- Pastikan .env sudah dikonfigurasi dengan database credentials"
echo "- Jika ada error, cek logs di storage/logs/"
echo "- Untuk update, upload ulang files dan jalankan script ini lagi"