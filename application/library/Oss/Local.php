<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;

class Local implements OssInterface
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param Image $image
     * @param \stdClass $params
     * @return array
     * @throws \Jcupitt\Vips\Exception
     */
    public function put(Image $image, \stdClass $params):OssResult
    {
        $srcRoot = $this->config->get('path') . '/' . date('Ymd');
        $rootPath = Config::rootPath($srcRoot);
        if (!file_exists($rootPath)) {
            mkdir($rootPath);
            chmod($rootPath, 644);
        }

        $file = $rootPath . '/'.$params->data->filename . '.png';
        $image->writeToFile($file);

        return new OssResult([
            'file' => $file,
            'src' => $srcRoot . '/'.$params->data->filename . '.png'
        ]);
    }

}
