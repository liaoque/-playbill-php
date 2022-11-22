<?php

namespace Oss;


class Aliyun extends Tencent implements OssInterface
{


    private $bucket;
    private $config;
    /**
     * @var OssClient
     */
    private OssClient $client;

    public function __construct($config)
    {
        $accessKeyId = $config->get('accessKeyId');
        $accessKeySecret = $config->get('accessKeySecret');
        $endpoint = $config->get('endpoint');
        $this->bucket = $config->get('bucket');
        $this->config = $config;

        $this->client = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    }

}
