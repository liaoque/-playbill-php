<?php

namespace PlayBill\Component;


use Jcupitt\Vips\Image;

interface ComponentInterface
{
    public function run(Image $image, array $changeData = []);
}
