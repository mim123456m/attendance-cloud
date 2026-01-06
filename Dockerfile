FROM php:8.4-apache

# ปิด MPM อื่น ๆ ให้เหลือ prefork ตัวเดียว
RUN a2dismod mpm_event mpm_worker || true \
 && a2enmod mpm_prefork

# ติดตั้ง MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# เปิด rewrite
RUN a2enmod rewrite

# เปลี่ยน Apache ให้ฟัง port 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/000-default.conf

# คัดลอกไฟล์เว็บ
COPY . /var/www/html/

WORKDIR /var/www/html
