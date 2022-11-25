<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;

class Local extends \Upload\Storage\Base implements OssInterface
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
    public function put(Image $image, \stdClass $params): OssResult
    {
        $srcRoot = '/img/' . date('Ymd');
        $rootPath = Config::rootPath($this->config->get('path') . $srcRoot);
        if (!file_exists($rootPath)) {
            if (!is_dir(Config::rootPath($this->config->get('path') . '/img/'))) {
                mkdir(Config::rootPath($this->config->get('path') . '/img/'));
            }
            mkdir($rootPath);
            chmod($rootPath, 666);
        }

        $file = $rootPath . '/' . $params->data->filename . '.png';
        $image->writeToFile($file);

        return new OssResult([
            'file' => $file,
            'src' => $srcRoot . '/' . $params->data->filename . '.png'
        ]);
    }

    public function upload(\Upload\File $file, $newName = null)
    {
        // TODO: Implement upload() method.
        $srcRoot = $this->config->get('path') . '/img/' . date('Ymd');
        $rootPath = Config::rootPath($srcRoot);
        $storage = new \Upload\Storage\FileSystem($rootPath);
        return $storage->upload($file, $newName);
    }
}
