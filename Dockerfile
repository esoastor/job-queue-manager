FROM php:8.1.0-fpm

WORKDIR /var/www

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    zip \
    unzip 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json ./
RUN composer install --no-scripts --no-autoloader

CMD ["php", "-S", "0.0.0.0:5000"]