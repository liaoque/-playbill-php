<?php

namespace Oss;

use HttpUtils\Config;
use Jcupitt\Vips\Image;

class Factory
{
    protected array $ossList = [];

    public function __construct(\Yaf_Config_Ini $config = null)
    {
        if (empty($config)) {
            $config = Config::get('oss');
        }
        $aliyun = $config->get('aliyun');
        if ($aliyun->enabled) {
            $this->ossList[] = new Aliyun($aliyun);
        }

        $tencent = $config->get('tencent');
        if ($tencent->enabled) {
            $this->ossList[] = new Tencent($tencent);
        }
    }

    public function put(Image $image)
    {
        return array_map(function ($item) use ($image) {
            return $item->put($image);
        }, $this->ossList);
    }


}
