# playbill-php

playbill是一款快速开发海报的工具。只需上传一张背景图，在对应的位置放上组件（文字、图片、二维码、头像）即可生成海报。并提供api替换图片、文字内容。

---
#### 图片存储：
- local
- fastdfs
- aliyun-oss
- tencent-oss
    
---

#### 快速开始
##### 1.docker安装
如果有现成的mongodb数据库,可以注释掉`doc/docker-compose-install.yml`的mongo部分
```shell script
docker-compose -f doc/docker-compose-install.yml up
```
##### 2.修改数据库配置文件`conf/database.ini`
```shell script
master.database='mongodb://root:63bbc509a3@mongo:27017'
```
##### 3.编辑海报
![编辑海报](./doc/1673255586846.jpg)

##### 4.生成代码
![编辑海报](./doc/1673255676472.jpg)

源码安装
[查看doc/INSTALL.md](./doc/INSTALL.md)


---

在线体验：


## 技术栈

### 后端

**[playbill-php](https://github.com/liaoque/playbill-php)**

| 框架 | 说明 |  版本 |
| --- | --- | --- |
| [php](https://www.php.net/) | 语言 |   7.4+ |
| [yaf](https://www.php.net/manual/zh/intro.yaf.php) | 应用开发框架 |   2.3.5+ |
| [MongoDB](https://www.mongodb.com/docs/manual/) | 数据库服务器 | 4.2 |
| [libvips](https://github.com/libvips/libvips) | 多线程图像处理库 | 8.13.0 |
| [php-vips](https://github.com/libvips/php-vips) | php-vips扩展 | 2.1.0 |

### 前端

**[playbill-vue](https://github.com/liaoque/playbill-vue)**

| 框架 | 说明 |  版本 |
| --- | --- | --- |
| [Vue](https://cn.vuejs.org/index.html) | JavaScript 框架 | 3.2.45 |
| [vue-pure-admin](https://github.com/xiaoxian521/vue-pure-admin) | 后台管理系统模版 | 3.5.0 |
