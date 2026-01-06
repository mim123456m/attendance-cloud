FROM php:8.4-apache

# PHP Extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

# Apache ฟังที่ 8080 (คงที่)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/000-default.conf

# Copy source
COPY . /var/www/html/
WORKDIR /var/www/html
