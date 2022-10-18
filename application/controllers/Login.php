<?php


class LoginController extends \Yaf_Controller_Abstract
{

    public function indexAction()
    {
        $params = $this->getRequest()->getParams();

        return AppResponse\AppResponse::success([
            'token' => 'eyJhbGciOiJIUzUxMiJ9.test'
        ]);

    }
}
