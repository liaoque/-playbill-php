<?php


class Logger implements \Psr\Log\LoggerInterface
{
    use \Psr\Log\LoggerTrait;


    public function log($level, $message, array $context = array()): void
    {
        // Do logging logic here.
        $appDirectory = Yaf_Dispatcher::getInstance()->getApplication()->getAppDirectory();
        file_put_contents($appDirectory . '/../storage/logs/error'.date('Ymd').'.log',
            "\r\n[$level]：$message---" . json_encode($context),
            FILE_APPEND
        );
//                var_dump(json_encode([$level, $message, $context]) );
    }
}
