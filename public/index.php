<?php
define("APPLICATION_PATH",  dirname(dirname(__FILE__)));
header("Access-Control-Allow-Origin: ".$_SERVER["HTTP_ORIGIN"] );
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Expose-Headers: *');
header('Access-Control-Allow-Headers: *');

$app  = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap() //call bootstrap methods defined in Bootstrap.php
->run();
