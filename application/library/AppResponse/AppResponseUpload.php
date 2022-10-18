<?php

namespace AppResponse;

class AppResponseUpload extends AppResponse
{
    const CODE = 1;

    public static function error($message)
    {
        return self::getView()->assign([
            'code' => self::CODE,
            'message' => $message,
        ]);
    }

}
