<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;
use Qcloud\Cos\Client;

class Tencent extends \Upload\Storage\Base implements OssInterface
{

    /**
     * @var Client
     */
    private Client $client;
    private $bucket;
    private $config;

    public function __construct($config)
    {
        $secretId = $config->get('secretId');
        $secretKey = $config->get('secretKey');
        $region = $config->get('region');
        $this->bucket = $config->get('bucket');
        $this->config = $config;

        $this->client = new Client([
            'region' => $region,
            'schema' => 'http',
            'credentials' => ['secretId' => $secretId, 'secretKey' => $secretKey]
        ]);
    }

    public function put(Image $image, \stdClass $params): OssResult
    {
        $file = date('Ymd') . '/' . $params->data->filename . '.png';
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $file,
            'Body' => $image->writeToBuffer('.png')
        ]);

        return new OssResult([
            'file' => $file,
            'src' => $file
        ]);
    }


    public function upload(\Upload\File $file, $newName = null)
    {
        if (is_string($newName)) {
            $fileName = strpos($newName, '.') ? $newName : $newName . '.' . $file->getExtension();
        } else {
            $fileName = $file->getNameWithExtension();
        }
        $newFile = date('Ymd') . '/' . $fileName;
        // TODO: Implement upload() method.
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $newFile,
            'Body' => fopen($file->getPathname(), 'rb')
        ]);
        return true;
    }
}
