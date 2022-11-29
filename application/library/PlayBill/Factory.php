<?php


namespace PlayBill;

use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Component\Background;
use PlayBill\Component\BackgroundImage;
use PlayBill\Utils\Alpha;

class Factory
{

    /**
     * @param $data
     * @param $changeData
     * @return null|Image
     * @throws Vips\Exception
     */
    public static function load($data, $changeData = [])
    {
        // 创建会话区域
        $out = null;

        $image = self::createArea($data);

        if (isset($data->data->backgroundImage) || isset($data->data->background)) {
            if (isset($data->data->background)) {
                $stdClass = new \stdClass();
                $stdClass->background = $data->data->background;
                $background = new Background($stdClass);
                $image = $background->run($image, $changeData);
            }
            if (isset($data->data->backgroundImage)) {
                $options = $data->data->backgroundImage;
                $options->uuid = $data->data->filename;
                $background = new BackgroundImage($options);
                $image = $background->run($image, $changeData);
            }
        } else {
            $image = $image->newFromImage([255, 255, 255]);
        }

        $objects = $data->data->objects;
        foreach ($objects as $row) {

            $componentType = $row->component_type;

            // 创建组件
            $overlay = null;
            $componentType = ucfirst($componentType);
            $className = "\\PlayBill\\Component\\{$componentType}";
            if (class_exists($className)) {
                $classNameObj = new $className($row);
                $image = $classNameObj->run($image, $changeData);
            }

        }

        return $image;
    }

    /**
     * @param $rows
     * @return Image
     */
    public static function createArea($rows)
    {

        if (!$rows->data->opacity) {
            return Image::black($rows->data->width / $rows->data->zoom, $rows->data->height / $rows->data->zoom);
        }

        $width = $rows->data->width / $rows->data->zoom;
        $height = $rows->data->height / $rows->data->zoom;
        $render = <<<EOF
<svg viewBox="0 0 {width} {height}">
    <rect x="0" y="0" 
    height="{height}" 
    width="{width}" 
    fill-opacity="0"
    ></rect>
</svg>
EOF;
        $render = strtr($render, [
            '{width}' => $width,
            '{height}' => $height,
        ]);
        return Image::svgload_buffer($render);
    }


}
