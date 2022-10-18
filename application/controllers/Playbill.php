<?php


class PlaybillController extends Yaf_Controller_Abstract
{
    public function queryAction($id = 0)
    {

    }

    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        var_dump($params);
    }



}
