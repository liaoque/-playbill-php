<?php


use Jcupitt\Vips;

class IndexController extends \Yaf_Controller_Abstract
{
    // default action name
    /**
     * 默认初始化方法，如果不需要，可以删除掉这个方法
     * 如果这个方法被定义，那么在Controller被构造以后，Yaf会调用这个方法
     */
    public function init()
    {
        $this->getView()->assign("header", "Yaf Example");
    }

    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/{&$APP_NAME&}/index/index/index/name/{&$AUTHOR&} 的时候, 你就会发现不同
     */
    public function indexAction()
    {
        $get = $this->getRequest()->getQuery("name", "default value");
        $this->getView()->assign("content", [
            'aa' => $get
        ]);

    }


}
