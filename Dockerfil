FROM php:8.4-apache

# ติดตั้ง MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# เปิด mod rewrite
RUN a2enmod rewrite

# คัดลอกไฟล์เข้า container
COPY . /var/www/html/

WORKDIR /var/www/html
