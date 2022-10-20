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
            $insert = $bulkWrite->insert($params);
            $this->manager->executeBulkWrite('playbill.poster', $bulkWrite);
            $oid = $insert->__toString();
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
            throw new Yaf_Exception('11', \AppResponse\AppResponsePlayBill::CODE_VIEW);
        }
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $this->manager->executeQuery('playbill.poster', $query);
//var_dump($rows->toArray());exit();
        $data = $rows->toArray()[0];



//
//        $image = Image::black(375, 667)->newFromImage([255, 255, 255]);
//        $text = Vips\Image::text('Hello world!', [
//            'font' => 'sans 160',
//            'width' => 10
//        ]);
//        $red = $text->newFromImage([255, 0, 0])->copy(['interpretation' => 'srgb']);
//        $overlay = $red->bandjoin($text);
//        $out = $image->composite($overlay, "over", ['x' => 10, 'y' => 10]);

        $out = \PlayBill\Factory::load($data);

        if ($out) {
            $writeToBuffer = $out->writeToFile('tiny2.jpg');
//            $writeToBuffer = $out->writeToBuffer('tiny.jpg');
        }
//        var_dump("data:image/jpeg;base64,".base64_encode($writeToBuffer));
        exit();
    }


}
