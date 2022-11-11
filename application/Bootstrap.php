<?php

use HttpUtils\Config;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

include_once "../vendor/autoload.php";

/* bootstrap class should be defined under ./application/Bootstrap.php */


class Bootstrap extends Yaf_Bootstrap_Abstract
{

    public function _initLog()
    {
        Jcupitt\Vips\Config::setLogger(new Logger);
    }

    public function _initConfig()
    {
        $config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config", $config);
    }

    public function _initError(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->setErrorHandler(function () {
            var_dump("捕獲錯誤");
            var_export(func_get_args());
            exit();
        }, YAF_ERR_STARTUP_FAILED | YAF_ERR_ROUTE_FAILED | YAF_ERR_DISPATCH_FAILED |
            YAF_ERR_AUTOLOAD_FAILED | YAF_ERR_NOTFOUND_MODULE | YAF_ERR_NOTFOUND_CONTROLLER | YAF_ERR_NOTFOUND_ACTION
            | YAF_ERR_NOTFOUND_VIEW | YAF_ERR_CALL_FAILED | YAF_ERR_TYPE_ERROR);

        set_exception_handler(function ($e) {
            echo json_encode([
                'code' => -1,
                'message' => $e->getMessage(),
            ]);
            exit();
        });
    }

    public function _initRequest(Yaf_Dispatcher $dispatcher)
    {
        $explode = explode(',', Yaf_Registry::get("config")->get('upload.urls'));
        if (in_array($dispatcher->getRequest()->getControllerName(), $explode)) {
            return;
        }
        $data = file_get_contents('php://input');
        if ($data) {
            $jsonDecode = json_decode($data, true);
            foreach ($jsonDecode as $key => $value) {
                $dispatcher->getRequest()->setParam($key, $value);
            }
        }
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        $config = Config::get('routes');
        $router = $dispatcher->getRouter();
        $router->addConfig($config->get('routes'));
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
//        var_dump(__METHOD__);
    }


    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        if ($dispatcher->getRequest()->getControllerName() == 'error') {
            return;
        }
        if (!Yaf_Registry::has(JsonView::class)) {
            Yaf_Registry::set(JsonView::class, new JsonView());
        }
        $dispatcher->setView(Yaf_Registry::get(JsonView::class));
    }

    public function _initDatabase(Yaf_Dispatcher $dispatcher)
    {
        if (!Yaf_Registry::has('Manager')) {
            $manager = new MongoDB\Driver\Manager(Config::get('database'));
            Yaf_Registry::set(MongoDB\Driver\Manager::class, $manager);
        }
//        $dispatcher->setView(Yaf_Registry::get(JsonView::class));
    }

}
