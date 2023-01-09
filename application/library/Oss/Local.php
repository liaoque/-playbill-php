<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;

class Local extends \Upload\Storage\Base implements OssInterface
{
    private $config;
    private $filePath;

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
            chmod($rootPath, 0755);
        }

        $file = $rootPath . '/' . $params->data->filename . '.png';
        $image->writeToFile($file);
        $this->filePath = $srcRoot . '/' . $params->data->filename . '.png';
        return new OssResult([
            'file' => $file,
            'src' => $srcRoot . '/' . $params->data->filename . '.png'
        ]);
    }

    public function upload(\Upload\File $file, $newName = null)
    {
        // TODO: Implement upload() method.
        $srcRoot = '/img/' . date('Ymd');
        $rootPath = Config::rootPath($this->config->get('path') . $srcRoot);
        if (!is_dir($rootPath)) {
            mkdir($rootPath);
        }
        $storage = new \Upload\Storage\FileSystem($rootPath);
        if (is_string($newName)) {
            $fileName = strpos($newName, '.') ? $newName : $newName . '.' . $file->getExtension();
        } else {
            $fileName = $file->getNameWithExtension();
        }
        $this->filePath = $srcRoot . '/' . $fileName;
        return $storage->upload($file, $newName);
    }

    public function getFilePath(): string
    {
        // TODO: Implement getFilePath() method.
        return $this->filePath;
    }
}
