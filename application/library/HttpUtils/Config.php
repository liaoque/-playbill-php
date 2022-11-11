<?php


namespace HttpUtils;


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

}
