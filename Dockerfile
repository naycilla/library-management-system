FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project into container
COPY src/ /var/www/html/

# Set working dir
WORKDIR /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html
