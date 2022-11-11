<?php

use Jcupitt\Vips;
use Jcupitt\Vips\Image;

class PlaybillController extends Yaf_Controller_Abstract
{

    /**
     * @param int $id
     * @return mixed
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function queryAction($id = 0)
    {
        if (empty($id)) {
            return \AppResponse\AppResponse::success([]);
        }

        $poster = new Poster();
        $row = $poster->getRowById($id);
        return \AppResponse\AppResponse::success($row);
    }

    /**
     * @return mixed
     * @throws Vips\Exception
     * @throws Yaf_Exception
     */
    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        if (empty($params)) {
            throw new Yaf_Exception('参数不能为空', \AppResponse\AppResponsePlayBill::PARAMS_EMPTY);
        }

        $out = \PlayBill\Factory::load($params);
        if (empty($out)) {
            throw new Yaf_Exception('参数不正确', \AppResponse\AppResponsePlayBill::PARAMS_VIPS);
        }

        $factory = new \Oss\Factory();
        $data = $factory->put($out);

        $poster = new Poster();
        $params['url'] = $data['url'];
        $oid = $poster->save($params);

        return \AppResponse\AppResponse::success(['oid' => $oid]);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Vips\Exception
     * @throws Yaf_Exception
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function viewAction($id = 0)
    {
        if (empty($id)) {
            $params = $this->getRequest()->getParams();
            if (empty($params)) {
                throw new Yaf_Exception('参数不能为空', \AppResponse\AppResponsePlayBill::PARAMS_EMPTY);
            }

            $params = json_decode(json_encode($params));
            $out = \PlayBill\Factory::load($params);
            $writeToBuffer1 = $out->writeToBuffer(".png");
            return \AppResponse\AppResponse::success(['src' => "data:image/png;base64," . base64_encode($writeToBuffer1)]);
        }

        $poster = new Poster();
        $data = $poster->getRowById($id);
        $out = \PlayBill\Factory::load($data);
        $writeToBuffer1 = $out->writeToBuffer(".png");
        return \AppResponse\AppResponse::success(['src' => "data:image/png;base64," . base64_encode($writeToBuffer1)]);
    }


}
