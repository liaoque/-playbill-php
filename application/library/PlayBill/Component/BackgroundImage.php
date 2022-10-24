<?php


namespace PlayBill\Component;


use Jcupitt\Vips\Config;
use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interpretation;
use Jcupitt\Vips\VipsOperation;
use PlayBill\Component\ComponentInterface;
use PlayBill\Utils\Alpha;
use PlayBill\Utils\Color;

class BackgroundImage extends AbstractComponent implements ComponentInterface
{

    /**
     * @param Image $image
     * @return Image
     * @throws Exception
     */
    public function run(Image $image)
    {
        $fill = $this->options->fill;
        $src = $this->options->src;
//         TODO: Implement run() method.
        if ($fill) {
            $colors = Color::rgb2rgba($fill);
            $image = $image->newFromImage([$colors[1], $colors[2], $colors[3]]);
        }

//        if ($src) {
//            $file_get_contents = file_get_contents($src);
//
//            $im = Image::newFromBuffer($file_get_contents);
//
//            $im = Alpha::addAlpha($im);
//
//            $image = $image->composite2($im, "over");
//        }

        if (!$fill && !$src) {
            $image = $image->newFromImage([255, 255, 255]);
        }
        return $image;

    }
}
