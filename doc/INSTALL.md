- libjpeg -- JPEG 图像压缩库
- libexif -- 对jpeg图片进行exif信息的写入,读取,修改等操作
- librsvg -- C 语言编写的非常快速的 SVG 渲染引擎
- PDFium -- Chromium 的 PDF 渲染引擎
- poppler-glib -- 一个PDF渲染库
- libgsf-1 -- GSF文件处理
- libtiff -- TIFF库
- cgif -- GIF编码器
- fftw3 -- FFTW是一个免费的快速C例程集合，用于在一个或多个维度上计算离散傅里叶变换。
- lcms2 -- vips_icc_import(), vips_icc_export() and vips_icc_transform() 可以使用ICC配置文件处理图像
- libspng -- png 图像压缩库
- libimagequant, quantizr -- 支持8位调色板化的PNG和GIF
- ImageMagick, or optionally GraphicsMagick -- 支持libMagick文件
- pangocairo -- 文本渲染库
- orc-0.4 -- 编译器加速
- matio -- Matlab文件支持
- cfitsio -- FITS文件支持
- libwebp -- WebP文件支持
- libniftiio -- NIfTI文件支持
- OpenEXR -- OpenEXR文件支持
- OpenJPEG -- JPEG2000文件支持
- libjxl -- JPEG-XL文件支持
- OpenSlide -- Aperio, Hamamatsu, Leica, MIRAX, Sakura, Trestle, and Ventana文件支持
- libheif -- HEIC，AVIF文件支持

#### 升级cmake
```shell script
yum remove cmake
wget https://github.com/Kitware/CMake/releases/download/v3.25.0-rc2/cmake-3.25.0-rc2.tar.gz
tar -zxvf cmake-3.25.0-rc2.tar.gz
cd cmake-3.25.0-rc2
./configure
gmake
```

#### 安装 meson, ninja
```shell script
pip3  install  meson -i https://pypi.doubanio.com/simple
pip3 install ninja -i https://pypi.doubanio.com/simple
```


#### 安装 libvips
```shell script
yum install libjpeg-turbo-devel.x86_64 \
            libexif-devel.x86_64 \
            librsvg2-devel.x86_64 \
            libtiff-devel.x86_64  \
            pango-devel.x86_64  \
            fftw-devel.x86_64  \
            orc-devel.x86_64

wget https://github.com/randy408/libspng/archive/v0.7.2.tar.gz
tar  -zxvf v0.7.2.tar.gz 
cd libspng-0.7.2/
mkdir cbuild
cd cbuild
cmake .. # Don't forget to set optimization level!
make
make install
ln -s /usr/local/lib/libspng.so /usr/lib/libspng.so 
ln -s /usr/lib64/pkgconfig/libspng.pc /usr/local/lib64/pkgconfig/libspng.pc 

- 要求 libimagequant, quantizr 才可以用cgif
git clone https://github.com/dloebl/cgif
cd cgif/
meson setup --prefix=/usr build
meson install -C build

wget https://github.com/webmproject/libwebp/archive/refs/tags/v1.2.4.tar.gz
tar -zxvf v1.2.4.tar.gz
cd libwebp-1.2.4
mkdir build && cd build && cmake ../
make
make install
ln -s  /usr/local/lib64/pkgconfig/libwebp.pc  /usr/lib64/pkgconfig/libwebp.pc
ln -s  /usr/local/lib64/pkgconfig/libwebpdecoder.pc  /usr/lib64/pkgconfig/libwebpdecoder.pc
ln -s  /usr/local/lib64/pkgconfig/libwebpdemux.pc  /usr/lib64/pkgconfig/libwebpdemux.pc
ln -s  /usr/local/lib64/pkgconfig/libwebpmux.pc  /usr/lib64/pkgconfig/libwebpmux.pc

rm -rf build-dir
meson setup build-dir
cd build-dir/
meson compile
meson test
meson install
```

#### 安装 mongodb
```shell script
vim /etc/yum.repos.d/mongodb-org-4.2.repo
[mongodb-org-4.2]
name=MongoDB Repository
baseurl=https://repo.mongodb.org/yum/redhat/$releasever/mongodb-org/4.2/x86_64/
gpgcheck=1
enabled=1
gpgkey=https://www.mongodb.org/static/pgp/server-4.2.asc

yum install  mongodb-org
useradd mongod
passwd mongod

su mongod

sudo mkdir -p /var/lib/mongo
sudo mkdir -p /var/log/mongodb
sudo chown -R mongod:mongod  /var/lib/mongo
sudo chown -R mongod:mongod  /var/log/mongodb
sudo systemctl daemon-reload
sudo systemctl start mongod

wget https://pecl.php.net/get/mongodb-1.6.1.tgz
tar -zxvf mongodb-1.6.1.tgz 
cd mongodb-1.6.1/
phpize
./configure 
make && make install
```
