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
            'title' => $row->title,
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
        $title = $params['data']['title'];
        unset($params['data']['title']);
        $params['title'] = $title;
        $oid = $poster->save($params);

        return \AppResponse\AppResponse::success([
            'oid' => $oid,
            'src' => \AppUtils\Config::baseUrl($ossResult->getSrc())
        ]);
    }

    /**
     * @param string $id
     * @param int $base64
     * @return mixed
     * @throws Vips\Exception
     * @throws Yaf_Exception
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function viewAction($id = '', $base64 = 1)
    {
        if (empty($id)) {
            $params = $this->getRequest()->getParams();
            if (empty($params)) {
                throw new Yaf_Exception('参数不能为空', \AppResponse\AppResponsePlayBill::PARAMS_EMPTY);
            }
            $params = json_decode(json_encode($params));
            $out = \PlayBill\Factory::load($params);
        } else {
            $params = $this->getRequest()->getParams();
            $poster = new PosterModel();
            $data = $poster->getRowById($id);
            $out = \PlayBill\Factory::load($data, $params);
            $params = $data;
        }

        if ($base64) {
            $writeToBuffer1 = $out->writeToBuffer( $params->data->mime_type);
            $mimeType = trim($params->data->mime_type, ".");
            return \AppResponse\AppResponse::success(['src' => "data:image/{$mimeType};base64," . base64_encode($writeToBuffer1)]);
        }
        $factory = new \Oss\Factory();
        $ossResult = $factory->put($out, $params);
        return \AppResponse\AppResponse::success(['src' => \AppUtils\Config::baseUrl($ossResult->getSrc())]);
    }


    public function templatesAction($page = 1, $limit = 10)
    {
        $keyword = $this->getRequest()->getQuery("keyword", "");
        $filter = [];
        if ($keyword) {
            $filter = [
                'title' => new \MongoDB\BSON\Regex($keyword)
            ];
        }

        $poster = new PosterModel();
        $result = $poster->getAll($filter, $page, $limit);

        $result = array_map(function ($item) {
            if (empty($item->src)) {
                return [];
            }
            return [
                'id' => $item->_id->__toString(),
                'src' => \AppUtils\Config::baseUrl($item->src),
                'title' => $item->title,
            ];
        }, $result->toArray());

        return \AppResponse\AppResponse::success(array_values(array_filter($result)));
    }

}
