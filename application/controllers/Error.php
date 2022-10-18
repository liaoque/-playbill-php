<?php

class ErrorController extends \Yaf_Controller_Abstract
{

    public function indexAction()
    {
        $this->display('403 forbidden');
        return false;
    }

    public function errorAction()
    {
        $exception = $this->getRequest()->getException();
        try {
            throw $exception;
        } catch (Yaf_Exception_LoadFailed $e) {

//

//加载失败
            $this->getView()->assign("code", $exception->getCode());
            $this->getView()->assign("message", $exception->getMessage());
        } catch (Yaf_Exception $e) {

//其他错误
            $this->getView()->assign("code", $exception->getCode());
            $this->getView()->assign("message", $exception->getMessage());
        }

    }
}
