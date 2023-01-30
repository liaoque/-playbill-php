<?php

namespace PlayBill\Utils;

use Jcupitt\Vips\Image;

class Color
{
    public static function h2rgba(string $color): array
    {
        if (strlen($color) == 4) {
            list($r, $g, $b) = sscanf($color, "#%x%x%x");
            return $rgb_color = [$r, $g, $b];
        }
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        return $rgb_color = [$r, $g, $b];
    }


    public static function rgb2rgba(string $color): array
    {
        preg_match('/rgb\((\d+),(\d+),(\d+)\)/', $color, $colors);
        return [$colors[1], $colors[2], $colors[3]];
    }

    public static function auto2rgba(string $color): array
    {
        if($color[0] == '#'){
            return self::h2rgba($color);
        }
        return self::rgb2rgba($color);
    }
}