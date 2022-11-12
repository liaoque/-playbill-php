<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;

class OssResult
{

    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }


    public function getSrc(){
        return $this->result['src'];
    }

}
