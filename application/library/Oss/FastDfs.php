<?php

namespace Oss;


use AppUtils\Config;
use Jcupitt\Vips\Image;

class FastDfs implements OssInterface
{


    /**
     * @var OssClient
     */
    private OssClient $client;
    private $config;
    private ?string $filePath;

    public function __construct($config)
    {

        $this->config = $config;

        $this->client = new \Eelly\FastDFS\Client([
            'host' => $config->get('host'),
            'port' => $config->get('port'),
            'group' => $config->get('group'),
        ]);
    }

    public function put(Image $image, \stdClass $params): OssResult
    {
        // TODO: Implement put() method.
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

        $uploadFile = $this->client->uploadFile($file);

        $this->filePath = $uploadFile;
        return new OssResult([
            'file' => $uploadFile,
            'src' => $uploadFile
        ]);
    }

    public function upload(\Upload\File $file, $newName = null)
    {
        // TODO: Implement upload() method.
        $this->client->uploadFile($file->getRealPath());
        return true;

    }

    public function getFilePath(): string
    {
        // TODO: Implement getFilePath() method.
        return $this->filePath;
    }
}
