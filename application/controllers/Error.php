<?php

class ErrorController extends \Yaf_Controller_Abstract
{
    /**
     * @throws Yaf_Exception_TypeError
     */
    public function indexAction()
    {
        $appDirectory = Yaf_Dispatcher::getInstance()->getApplication()->getAppDirectory();
        preg_match('/\d+/', $this->getRequest()->getRequestUri(), $data);

        if (!($this->getView() instanceof Yaf_View_Simple)) {
            $yafViewSimple = new Yaf_View_Simple($appDirectory . '/views');
            Yaf_Dispatcher::getInstance()->setView($yafViewSimple);
        }
        $this->getView()->assign("code", $data[0]);
    }

}
