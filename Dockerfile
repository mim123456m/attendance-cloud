FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

# ให้ Apache ใช้ PORT จาก Railway
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:${PORT}/' /etc/apache2/sites-enabled/000-default.conf

COPY . /var/www/html/
WORKDIR /var/www/html
ENV PORT=8080
