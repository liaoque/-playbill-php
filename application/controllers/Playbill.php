<?php

use Jcupitt\Vips;
use Jcupitt\Vips\Image;

class PlaybillController extends Yaf_Controller_Abstract
{

    /**
     * @param string $id
     * @return mixed
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function queryAction($id = '')
    {
        if (empty($id)) {
            return \AppResponse\AppResponse::success([]);
        }

        $poster = new PosterModel();
        $row = $poster->getRowById($id);
        return \AppResponse\AppResponse::success([
            'id' => $row->_id->__toString(),
            'data' => $row->data,
        ]);
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
        $params2 = json_decode(json_encode($params));
        $out = \PlayBill\Factory::load($params2);
        if (empty($out)) {
            throw new Yaf_Exception('参数不正确', \AppResponse\AppResponsePlayBill::PARAMS_VIPS);
        }

        $factory = new \Oss\Factory();
        $ossResult = $factory->put($out, $params2);

        $poster = new PosterModel();
        $params['src'] = $ossResult->getSrc();
        $oid = $poster->save($params);

        return \AppResponse\AppResponse::success([
            'oid' => $oid,
            'src' => $ossResult->getSrc()
        ]);
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

        $poster = new PosterModel();
        $data = $poster->getRowById($id);
        $out = \PlayBill\Factory::load($data);
        $writeToBuffer1 = $out->writeToBuffer(".png");
        return \AppResponse\AppResponse::success(['src' => "data:image/png;base64," . base64_encode($writeToBuffer1)]);
    }


    public function templatesAction($page = 1, $limit = 10)
    {
        $poster = new PosterModel();
        $result = $poster->getAll($page, $limit);

        $result = array_map(function ($item) {
            if (empty($item->src)) {
                return [];
            }
            return [
                'id' => $item->_id->__toString(),
                'src' => 'http://yaf.mzq/' . $item->src,
            ];
        }, $result->toArray());

        return \AppResponse\AppResponse::success(array_values(array_filter($result)));
    }

}
