FROM php:7.1-cli-alpine 
RUN apk upgrade --update && apk add --update libmcrypt-dev openssl git zip unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql mbstring mcrypt mysqli

WORKDIR /app

COPY composer.json composer.lock /app/
RUN composer install --no-autoloader --no-scripts

COPY . /app
RUN composer dumpautoload


CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080