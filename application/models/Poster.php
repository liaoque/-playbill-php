<?php


class Poster
{

    /**
     * @var mixed
     */
    protected MongoDB\Driver\Manager $manager;

    public function __construct()
    {
        $this->manager = Yaf_Registry::get(MongoDB\Driver\Manager::class);
    }

    public function save($params)
    {
        if (isset($params['data']['oid'])) {
            $oid = $params['data']['oid'];
            $bulkWrite = new MongoDB\Driver\BulkWrite();
            $bulkWrite->update([
                '_id' => new \MongoDB\BSON\ObjectId($oid)
            ], [
                '$set' => $params
            ]);
            $this->manager->executeBulkWrite('playbill.poster', $bulkWrite);
            return $oid;
        }

        $bulkWrite = new MongoDB\Driver\BulkWrite();
        $insert = $bulkWrite->insert($params);
        $this->manager->executeBulkWrite('playbill.poster', $bulkWrite);
        $oid = $insert->__toString();
        return $oid;
    }


    /**
     * @param $id
     * @return mixed
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function getRowById($id)
    {
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $this->manager->executeQuery('playbill.poster', $query);
        return $rows[0];
    }
}
