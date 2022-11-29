FROM php:7.4-fpm

RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak


RUN echo deb https://mirrors.aliyun.com/debian/ bullseye main non-free contrib  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian/ bullseye main non-free contrib  >> /etc/apt/sources.list && \
 echo deb https://mirrors.aliyun.com/debian-security/ bullseye-security main  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian-security/ bullseye-security main  >> /etc/apt/sources.list && \
 echo deb https://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib  >> /etc/apt/sources.list && \
 echo deb https://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib  >> /etc/apt/sources.list && \
 echo deb-src https://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib  >> /etc/apt/sources.list

RUN  apt-get update && apt-get install -y libvips42 && apt-get install libffi-dev


RUN  docker-php-ext-install ffi
