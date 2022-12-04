FROM php:7.4-fpm

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

RUN  apt-get update && apt-get install -y libvips42 && apt-get install -y libffi-dev && apt-get install -y zlib1g-dev && apt-get install -y apt-utils && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev


ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    COMPOSER_VERSION=2.4.4


RUN set -ex && docker-php-ext-install ffi  \
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


WORKDIR /www/html

#初次安装注释掉，否则报错
# RUN composer install --no-dev -o

EXPOSE 9000

CMD [ "php", "./your-script.php" ]
