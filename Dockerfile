FROM php:8.4-apache

# ติดตั้ง MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# เปิด mod rewrite
RUN a2enmod rewrite

# ให้ Apache ฟังที่ PORT ของ Railway
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/' /etc/apache2/sites-available/000-default.conf

# copy source code
COPY . /var/www/html/

WORKDIR /var/www/html
