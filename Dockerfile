FROM php:8.4-apache

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# rewrite
RUN a2enmod rewrite

# เปลี่ยน Apache ไปฟัง port 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/000-default.conf

COPY . /var/www/html/
WORKDIR /var/www/html

EXPOSE 8080
