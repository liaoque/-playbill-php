<?php

use Jcupitt\Vips;
use Jcupitt\Vips\Image;

class PlaybillController extends Yaf_Controller_Abstract
{
    protected MongoDB\Driver\Manager $manager;

    public function init()
    {
        $this->manager = Yaf_Registry::get(MongoDB\Driver\Manager::class);
    }

    /**
     * @param int $id
     * @return bool
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function queryAction($id = 0)
    {
        if (empty($id)) {
            return \AppResponse\AppResponse::success([]);
        }
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $this->manager->executeQuery('playbill.poster', $query);
        return \AppResponse\AppResponse::success($rows->toArray());
    }

    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $oid = '';

        if ($params) {
            $bulkWrite = new MongoDB\Driver\BulkWrite();
            if (isset($params['data']['oid'])) {
                $oid = $params['data']['oid'];
                $bulkWrite->update([
                    '_id' => new \MongoDB\BSON\ObjectId($oid)
                ], [
                    '$set' => $params
                ]);
                $this->manager->executeBulkWrite('playbill.poster', $bulkWrite);
            } else {
                $insert = $bulkWrite->insert($params);
                $this->manager->executeBulkWrite('playbill.poster', $bulkWrite);
                $oid = $insert->__toString();
            }
        }
        return \AppResponse\AppResponse::success(['oid' => $oid]);
    }

    /**
     * @param int $id
     * @throws Yaf_Exception
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws Vips\Exception
     */
    public function viewAction($id = 0)
    {
        if (empty($id)) {
            $params = $this->getRequest()->getParams();
            if (empty($params)) {
                throw new Yaf_Exception('11', \AppResponse\AppResponsePlayBill::CODE_VIEW);
            }
            $params = json_decode(json_encode($params));
            $out = \PlayBill\Factory::load($params);
            $writeToBuffer1 = $out->writeToBuffer(".png");
            return  \AppResponse\AppResponse::success(['src' => "data:image/png;base64,".base64_encode($writeToBuffer1)]);
        }
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $this->manager->executeQuery('playbill.poster', $query);
        $data = $rows->toArray()[0];

        $out = \PlayBill\Factory::load($data);

        if ($out) {
            $writeToBuffer = $out->writeToFile('tiny2.png');
        }
    }


}
