FROM php:7.4-fpm as playbill-php

RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak
RUN > /etc/apt/sources.list

RUN echo deb https://mirrors.aliyun.com/debian/ bullseye main non-free contrib  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian/ bullseye main non-free contrib  >> /etc/apt/sources.list && \
 echo deb https://mirrors.aliyun.com/debian-security/ bullseye-security main  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian-security/ bullseye-security main  >> /etc/apt/sources.list && \
 echo deb https://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib  >> /etc/apt/sources.list && \
 echo deb https://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib  >> /etc/apt/sources.list

RUN  apt-get update && apt-get install -y libvips42 libffi-dev zlib1g-dev apt-utils libfreetype6-dev libjpeg62-turbo-dev libpng-dev


ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"}


RUN set -ex && docker-php-ext-install ffi  \
      && echo ffi.enable=true >> /usr/local/etc/php/conf.d/docker-php-ext-ffi.ini \
      && docker-php-ext-install gd \
      && pecl channel-update pecl.php.net \
      && pecl install mongodb \
      && touch /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini \
      && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini \
      && pecl install yaf \
      && touch /usr/local/etc/php/conf.d/docker-php-ext-yaf.ini \
      && echo "extension=yaf.so" > /usr/local/etc/php/conf.d/docker-php-ext-yaf.ini \
      && php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');" \
      && php composer-setup.php \
      && mv composer.phar /usr/local/bin/composer \
      && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/


RUN useradd www
USER www

ARG PLAYBILL_PATH=/www/html/playbill
WORKDIR ${PLAYBILL_PATH}

ADD --chown=www:www ./application ${PLAYBILL_PATH}/application
ADD --chown=www:www ./conf ${PLAYBILL_PATH}/conf
ADD --chown=www:www ./doc ${PLAYBILL_PATH}/doc
ADD --chown=www:www ./examples ${PLAYBILL_PATH}/examples
ADD --chown=www:www ./public ${PLAYBILL_PATH}/public
ADD --chown=www:www ./vendor ${PLAYBILL_PATH}/vendor
ADD --chown=www:www ./vips-doc ${PLAYBILL_PATH}/vips-doc
ADD --chown=www:www ./storage ${PLAYBILL_PATH}/storage
ADD --chown=www:www ./composer.json ${PLAYBILL_PATH}/composer.json
ADD --chown=www:www ./composer.lock ${PLAYBILL_PATH}/composer.lock
ADD --chown=www:www ./.gitignore ${PLAYBILL_PATH}/.gitignore


FROM nginx:alpine as playbill-nginx
ARG PLAYBILL_PATH=/www/html/playbill
WORKDIR ${PLAYBILL_PATH}

COPY ./playbill.conf /etc/nginx/conf.d/



