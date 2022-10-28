<?php

namespace PlayBill\Utils;

use Jcupitt\Vips\Image;

class Alpha
{
    public static function hasAlpha(Image $im): bool
    {
        return $im->hasAlpha();
    }

    /**
     * @param Image $im
     * @return Image
     * @throws \Jcupitt\Vips\Exception
     */
    public static function addAlpha(Image $im): Image
    {
        if (!$im->hasAlpha()) {
            $maxAlpha = 255;
            if ($im->get('Type') == 26 || $im->get('Type') == 25) {
                $maxAlpha = 65535;
            }
            $im = $im->bandjoin_const($maxAlpha);
        }
        return $im;
    }
}
