<?php


namespace AppUtils;


class Config
{

    static protected array $configArray = [];

    /**
     * @param $iniName
     * @param $section
     * @return \Yaf_Config_Ini
     * @throws \Yaf_Exception_TypeError
     */
    public static function get($iniName, $section = null)
    {
        if (isset(self::$configArray[$iniName])) {
            return self::$configArray[$iniName];
        }

        if (empty($section)) {
            $section = \Yaf_Application::app()->getConfig()->get('application.env');
        }

        $config = \Yaf_Application::app()->getAppDirectory() . '/../conf/' . $iniName . '.ini';
        return self::$configArray[$iniName] = new \Yaf_Config_Ini($config, $section);
    }

    /**
     * @param $file
     * @return string
     */
    public static function rootPath($file)
    {
        return \Yaf_Application::app()->getAppDirectory() . '..' . $file;
    }

    public static function baseUrl($url = '')
    {
        $var = \Yaf_Dispatcher::getInstance()->getApplication()->getConfig()->get('application.base_url');
        return rtrim($var, '/') . DIRECTORY_SEPARATOR . ltrim($url, '/');
    }
}
