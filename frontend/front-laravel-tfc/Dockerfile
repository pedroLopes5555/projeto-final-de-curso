FROM php:8.1

# Update and install necessary packages
RUN apt-get update -y \
    && apt-get install -y openssl zip unzip git \
                          libonig-dev   # Install libonig-dev package for oniguruma support

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install pdo mbstring pdo_mysql

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install PHP dependencies using Composer
RUN composer install

# Expose port and specify CMD
EXPOSE 8181
CMD php artisan serve --host=0.0.0.0 --port=8181
