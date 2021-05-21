FROM php:8.0.6-fpm-buster

RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin --filename=composer
RUN apt-get update \
        && apt-get install -y \
        git \
        zip \
        unzip \
        vim 
WORKDIR /app
COPY . .

