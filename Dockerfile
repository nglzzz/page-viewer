FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    git \
    libpq-dev \
    && a2enmod rewrite \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
