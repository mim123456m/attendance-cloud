FROM php:8.4-apache

# ติดตั้ง MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# เปิด mod rewrite
RUN a2enmod rewrite

# Copy source
COPY . /var/www/html/

# ตั้ง Working directory
WORKDIR /var/www/html

# ให้ Apache ฟัง PORT จาก Railway
ENV PORT=8080
EXPOSE 8080

# เปลี่ยน Apache ให้ฟังที่ PORT
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-enabled/000-default.conf

CMD ["apache2-foreground"]
