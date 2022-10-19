<?php


class PlaybillController extends Yaf_Controller_Abstract
{
    public function queryAction($id = 0)
    {
        if (empty($id)) {
            return \AppResponse\AppResponse::success([]);
        }
        $manager = Yaf_Registry::get(MongoDB\Driver\Manager::class);
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $manager->executeQuery('playbill.poster', $query);
        return \AppResponse\AppResponse::success($rows->toArray());
    }

    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $oid = '';
        if ($params) {
            $manager = Yaf_Registry::get(MongoDB\Driver\Manager::class);
            $bulkWrite = new MongoDB\Driver\BulkWrite();
            $insert = $bulkWrite->insert($params);
            $manager->executeBulkWrite('playbill.poster', $bulkWrite);
            $oid = $insert->__toString();
        }
        return \AppResponse\AppResponse::success(['oid' => $oid]);
    }


}
