FROM php:7-alpine
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
COPY composer.json ./
COPY composer.lock ./
RUN composer install --no-scripts --no-autoloader
COPY . ./
# RUN apk add --update --no-cache \
#   make \
#   g++ \
#   automake \
#   autoconf \
#   libtool \
#   nasm \
#   libjpeg-turbo-dev
#RUN apt-get install php7.2-mysql
# RUN composer dump-autoload --optimize && \
# 	composer run-scripts post-install-cmd
