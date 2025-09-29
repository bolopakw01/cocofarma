# CocoFarma - Deployment Guide

## 📋 Prerequisites

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM (for assets)
- Web server (Apache/Nginx)

## 🚀 Quick Deployment Options

### Option 1: Shared Hosting (cPanel)

1. **Upload Files**
   ```bash
   # Zip your project (exclude node_modules, .git)
   zip -r cocofarma.zip . -x "node_modules/*" ".git/*" "*.log"
   # Upload via FTP or cPanel File Manager
   ```

2. **Database Setup**
   - Create MySQL database in cPanel
   - Import your local database schema

3. **Configuration**
   ```bash
   # Edit .env file
   APP_ENV=production
   APP_DEBUG=false
   DB_HOST=localhost
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_pass
   ```

4. **Run Deployment Script**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh production
   ```

### Option 2: VPS (Ubuntu/Debian)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install apache2 mysql-server php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Clone repository
cd /var/www
sudo git clone https://github.com/yourusername/cocofarma.git
cd cocofarma

# Run deployment
chmod +x deploy.sh
./deploy.sh production

# Configure Apache
sudo nano /etc/apache2/sites-available/cocofarma.conf
```

**Apache Virtual Host:**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/cocofarma/public

    <Directory /var/www/cocofarma/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/cocofarma_error.log
    CustomLog ${APACHE_LOG_DIR}/cocofarma_access.log combined
</VirtualHost>
```

```bash
# Enable site and restart Apache
sudo a2ensite cocofarma.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Option 3: Docker Deployment

```dockerfile
# Dockerfile
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
```

```yaml
# docker-compose.yml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8000:80"
    environment:
      - APP_ENV=production
      - APP_KEY=your_app_key_here
      - DB_HOST=db
      - DB_DATABASE=cocofarma
      - DB_USERNAME=root
      - DB_PASSWORD=password
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: cocofarma
      MYSQL_ROOT_PASSWORD: password
    volumes:
      - mysql_data:/var/lib/mysql

volumes:
  mysql_data:
```

### Option 4: Cloud Platforms

#### Heroku
```bash
# Create Procfile
echo "web: vendor/bin/heroku-php-apache2 public/" > Procfile

# Deploy
heroku create cocofarma-app
git push heroku main
```

#### DigitalOcean App Platform
- Connect your GitHub repository
- Set build command: `./deploy.sh production`
- Configure environment variables
- Set PHP version to 8.1+

#### Railway
- Connect GitHub repository
- Set build command and start command
- Configure database and environment variables

## 🔧 Post-Deployment Checklist

- [ ] Test database connection
- [ ] Run migrations: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Test admin login
- [ ] Check file permissions
- [ ] Configure backup system
- [ ] Set up SSL certificate
- [ ] Configure domain DNS

## 🛠 Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   ```bash
   # Check logs
   tail -f storage/logs/laravel.log

   # Check permissions
   chmod -R 755 storage bootstrap/cache
   ```

2. **Database Connection Error**
   - Verify database credentials in .env
   - Check if database exists
   - Ensure MySQL service is running

3. **Assets Not Loading**
   ```bash
   # Publish assets
   php artisan vendor:publish --tag=public
   ```

## 📞 Support

If you encounter issues during deployment, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Web server logs
3. PHP error logs
4. Database connection

## 🔒 Security Notes

- Change default passwords
- Use strong APP_KEY
- Enable HTTPS in production
- Regularly update dependencies
- Use environment variables for sensitive data