<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;

class Factory
{
    protected OssInterface $oss;

    public function __construct(\Yaf_Config_Ini $config = null)
    {
        if (empty($config)) {
            $config = Config::get('oss');
        }

        $local = $config->get('local');
        if ($local->enabled) {
            $this->oss = new Local($local);
        }

        $aliyun = $config->get('aliyun');
        if ($aliyun->enabled) {
            $this->oss = new Aliyun($aliyun);
        }

        $tencent = $config->get('tencent');
        if ($tencent->enabled) {
            $this->oss = new Tencent($tencent);
        }
    }

    public function put(Image $image, \stdClass $params): OssResult
    {
        return $this->oss->put($image, $params);
    }

    /**
     * @return OssInterface|Tencent
     */
    public function getStorage()
    {
        return $this->oss;
    }

}
