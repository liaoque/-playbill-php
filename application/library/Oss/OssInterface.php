<?php


namespace Oss;


use Jcupitt\Vips\Image;

interface OssInterface
{

    public function put(Image $image, \stdClass $params): OssResult;

    public function upload(\Upload\File $file, $newName = null);

}
