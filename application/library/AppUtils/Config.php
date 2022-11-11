<?php


namespace AppUtils;


class Config
{

    /**
     * @param $iniName
     * @param $section
     * @return \Yaf_Config_Ini
     * @throws \Yaf_Exception_TypeError
     */
    public static function get($iniName, $section = null)
    {
        if (empty($section)) {
            $section = \Yaf_Application::app()->getConfig()->get('application.env');
        }

        $config = \Yaf_Application::app()->getAppDirectory() . '/../conf/' . $iniName . '.ini';
        return new \Yaf_Config_Ini($config, $section);
    }

    /**
     * @param $file
     * @return string
     */
    public static function rootPath($file){
        return \Yaf_Application::app()->getAppDirectory() . '/../' . $file;
    }

    public static function baseHost(){
        $url = \Yaf_Dispatcher::getInstance()->getApplication()->getConfig()->get('application.base_host');
    }
}
