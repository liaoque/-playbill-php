<?php


namespace PlayBill;

use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Component\Background;

class Factory
{

    /**
     * @param $data
     * @return null|Image
     * @throws Vips\Exception
     */
    public static function load($data)
    {
        // 创建会话区域
        $out = null;
        $image = self::createArea($data);
        $objects = $data->data->objects;
        if (isset($data->data->backgroundImage)) {
            $background = new Background($data->data->backgroundImage);
            $overlay = $background->run();
            if ($overlay) {
                $image = $image->composite($overlay, "over", ['x' => 100, 'y' => 100]);
            }
        }

        foreach ($objects as $row) {

            // 创建组件
            $overlay = null;
            $className = "\\PlayBill\\{$row['component_type']}";
            if (class_exists($className)) {
                $classNameObj = new $className($row);
                $overlay = $classNameObj->run();
            }

            // 添加组件
            if ($overlay) {
                $image = $image->composite($overlay, "over", ['x' => 100, 'y' => 100]);
            }
        }

//        // 写入
//        if (isset($out)) {
//            $out->writeToFile('tiny.jpg');
//        }
        return $out;
    }

    /**
     * @return Image
     * @throws Vips\Exception
     */
    public static function createArea($rows)
    {
        return Image::black(375, 667)->newFromImage([255, 255, 255]);
    }


}
