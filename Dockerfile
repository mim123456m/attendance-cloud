FROM php:8.4-apache

# ติดตั้ง MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# เปิด mod rewrite
RUN a2enmod rewrite

# copy source
COPY . /var/www/html/

# copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /var/www/html

# ให้ container ใช้ start.sh
CMD ["/start.sh"]
