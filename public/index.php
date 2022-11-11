<?php
define("APPLICATION_PATH",  dirname(dirname(__FILE__)));

$env = include APPLICATION_PATH.'/conf/env.php';
$app  = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini", $env);
$app->bootstrap()->run();
