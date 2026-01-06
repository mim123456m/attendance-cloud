FROM php:8.4-apache

# Install MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy app
COPY . /var/www/html/

WORKDIR /var/www/html

# Railway uses PORT env
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

# Force Apache to listen on Railway PORT
CMD sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf \
 && sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-enabled/000-default.conf \
 && apache2-foreground
