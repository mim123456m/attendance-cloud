FROM php:8.4-apache

# ลบ MPM ทุกตัวก่อน
RUN a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2dismod mpm_prefork || true

# เปิดแค่ prefork ตัวเดียว
RUN a2enmod mpm_prefork

# PHP + MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# rewrite
RUN a2enmod rewrite

# ใช้ port 8080 (Railway)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/000-default.conf

COPY . /var/www/html/
WORKDIR /var/www/html
