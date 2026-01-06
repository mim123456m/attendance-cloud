FROM php:8.4-apache

# ปิด MPM ทั้งหมดก่อน (กัน error ซ้ำ)
RUN a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2dismod mpm_prefork || true

# เปิดแค่ prefork
RUN a2enmod mpm_prefork

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# rewrite
RUN a2enmod rewrite

# บังคับ Apache ฟัง 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/000-default.conf

# copy app
COPY . /var/www/html/
WORKDIR /var/www/html

# สำคัญมาก: บอก Railway ว่า container ใช้ 8080
EXPOSE 8080
