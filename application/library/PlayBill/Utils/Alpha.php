<?php

namespace PlayBill\Utils;

use Jcupitt\Vips\Image;

class Alpha
{
    public static function hasAlpha(Image $im): bool
    {
        return $im->hasAlpha();
    }

    public static function addAlpha(Image $im): Image
    {
        if (!$im->hasAlpha()) {
            $maxAlpha = 255;
            if ($im->get('Type') == 26 || $im->get('Type') == 25) {
                $maxAlpha = 65535;
            }

            $im = $im->bandjoin_const($maxAlpha)
                ->multiply([1, 1, 1, 0.5])
                ->cast("uchar");

//            $im = $im->replicate(1, $image->height / $image->height);

        }
        return $im;
    }
}