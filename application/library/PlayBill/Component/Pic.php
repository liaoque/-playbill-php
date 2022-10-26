<?php
namespace PlayBill\Component;


use Jcupitt\Vips;

class Pic implements ComponentInterface
{
    /**
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Vips\Image $image){
        return $image;
    }
}
