```shell script
cp /etc/apt/sources.list /etc/apt/sources.list.bak
apt install ca-certificates
apt update
apt-get install software-properties-common
apt install vim
apt install add-apt-repository
add-apt-repository ppa:lovell/cgif
apt-get update
apt-get install libcgif-dev
apt install     build-essential     ninja-build     python3-pip     bc     wget
pip3 install meson
apt install     libfftw3-dev     libopenexr-dev     libgsf-1-dev     libglib2.0-dev     liborc-dev     libopenslide-dev     libmatio-dev     libwebp-dev     libjpeg-turbo8-dev     libexpat1-dev     libexif-dev     libtiff5-dev     libcfitsio-dev     libpoppler-glib-dev     librsvg2-dev     libpango1.0-dev     libopenjp2-7-dev     libimagequant-dev
wget https://github.com/libvips/libvips/releases/download/v8.13.3/vips-8.13.3.tar.gz

tar -zxvf vips-8.13.3.tar.gz 
cd vips-8.13.3
meson build --libdir=lib --buildtype=release -Dintrospection=false
cd build
meson compile
meson test
meson install
apt install nginx
apt install php

apt-get install php-dev php-pear php7.4-curl php7.4-fpm php7.4-gd php7.4-mbstring php7.4-zip

pecl install mongodb
echo "extension=mongodb.so" >  /etc/php/7.4/mods-available/mongodb.ini
ln -s /etc/php/7.4/mods-available/mongodb.ini /etc/php/7.4/cli/conf.d/20-mongodb.ini 
ln -s /etc/php/7.4/mods-available/mongodb.ini /etc/php/7.4/fmp/conf.d/20-mongodb.ini 

pecl install yaf
echo "extension=yaf.so" >  /etc/php/7.4/mods-available/yaf.ini
ln -s /etc/php/7.4/mods-available/yaf.ini /etc/php/7.4/cli/conf.d/20-yaf.ini 
ln -s /etc/php/7.4/mods-available/yaf.ini /etc/php/7.4/fmp/conf.d/20-yaf.ini
 
echo "ffi.enable=true" >>  /etc/php/7.4/mods-available/ffi.ini


useradd nginx
useradd www

mkdir /run/php/

#/etc/php/7.4/fpm/pool.d

php-fpm7.4 -y /etc/php/7.4/fpm/php-fpm.conf 
nginx -c /etc/nginx/nginx.conf
```
