# Use official PHP image
FROM php:8.2-cli

# Install required dependencies (zip is often needed)
RUN apt-get update && apt-get install -y \
    unzip libzip-dev && docker-php-ext-install zip

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer (for PHP dependencies)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "."]
