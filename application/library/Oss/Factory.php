<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;

class Factory
{
    protected OssInterface $oss;

    protected array $uploadTools = [
        'local' => Local::class,
        'aliyun' => Aliyun::class,
        'tencent' => Tencent::class,
        'fastdfs' => FastDfs::class,
    ];

    public function __construct(\Yaf_Config_Ini $config = null)
    {
        if (empty($config)) {
            $config = Config::get('oss');
        }

        foreach ($this->uploadTools as $key => $value) {
            $local = $config->get($key);
            if ($local->enabled) {
                $this->oss = new $value($local);
                break;
            }
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
