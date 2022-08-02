FROM php:7.4-fpm

ENV COMPOSER_HOME /tmp

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
  && pecl install xdebug \
  && docker-php-ext-install -j$(nproc) zip pdo pdo_mysql \
  && docker-php-ext-enable xdebug \
  && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;

COPY install-composer.sh .
RUN sh ./install-composer.sh && rm ./install-composer.sh
