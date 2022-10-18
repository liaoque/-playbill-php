 |- application：项目程序代码主要的文件夹
     |- controllers：主要存放MVC三层架构中的 C，即控制器层  
     |- library：主要存放本地类库文件
     |- models：即为MVC中的M，模型层
     |- modules：模块层
     |- plugins：主要存放一些插件，例如：API授权检测插件，具体在实战中详细说明
     |- views：MVC三层结构中的V，即为视图层
     Bootstrap.php： Bootstrap, 也叫做引导程序. 它是Yaf提供的一个全局配置的入口
    
 |- conf：主要存放框架配置相关的文件，例如：application.ini
     application.ini
     
 |- public: web部署根目录文件夹，主要存放：入口文件，静态资源文件
     index.php
     |- css
     |- img
     |- js
     
 |- vendor：composer第三方库文件存放



4. runtime 缓存日志文件夹

主要存放程序运行过程中产生的缓存和日志临时文件夹，由开发者开发产生

5. tests 单元测试目录

主要存放单元测试相关文件，具体如何使用，在Yaf与单元测试章节会说明

