<?php

namespace AppResponse;


class AppResponse
{

    public static function success($data = [])
    {
        return self::getView()->assign([
            'code' => 0,
            'message' => '',
            'info' => $data
        ]);
    }


    /**
     * @return \Yaf_View_Interface
     */
    protected static function getView()
    {
        return \Yaf_Registry::get(\JsonView::class);
    }

}
