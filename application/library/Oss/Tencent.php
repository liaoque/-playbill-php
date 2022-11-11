<?php

namespace Oss;

use Jcupitt\Vips\Image;
use Qcloud\Cos\Client;

class Tencent implements OssInterface
{


    /**
     * @var Client
     */
    private Client $client;

    public function __construct($config)
    {
        $secretId = $config->get('secretId');
        $secretKey = $config->get('secretKey');
        $region = $config->get('region');
        $bucket = $config->get('bucket');

        $this->client = new Client([
            'region' => $region,
            'schema' => 'https',
            'credentials' => ['secretId' => $secretId, 'secretKey' => $secretKey]
        ]);

    }

    public function put(Image $image)
    {
        $this->client->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $image,
            'Body' => $image
        ));

    }


}
