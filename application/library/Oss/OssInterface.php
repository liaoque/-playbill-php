<?php


namespace Oss;


use Jcupitt\Vips\Image;

interface OssInterface
{

    public function put(Image $image);

}
