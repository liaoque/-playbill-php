<?php


namespace PlayBill\Component;


use Jcupitt\Vips\Config;
use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interpretation;
use Jcupitt\Vips\VipsOperation;
use PlayBill\Component\ComponentInterface;

class Background extends AbstractComponent implements ComponentInterface
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
            preg_match('/rgb\((\d+),(\d+),(\d+)\)/', $fill, $colors);
            $image = $image->newFromImage([$colors[1], $colors[2], $colors[3]]);
        }

        if ($src) {
            $file_get_contents = file_get_contents($src);

            $im = Image::newFromBuffer($file_get_contents);
            if (!$im->hasAlpha()) {
                $maxAlpha = 255;
                if ($im->get('Type') == 26 || $im->get('Type') == 25) {
                    $maxAlpha = 65535;
                }

                $im = $im->bandjoin_const($maxAlpha)
                    ->multiply([1, 1, 1, 0.5])
                    ->cast("uchar");

                $im = $im->replicate(1, $image->height / $image->height);

            }

            $width = $im->width;
            if ($this->options->scaleX) {
                $width = $im->width * $this->options->scaleX;
            }

            $height = $im->height;
            if ($this->options->scaleY) {
                $height = $im->height * $this->options->scaleX;
            }

            if($width && $height){
                $im = $im->replicate(
                    1 + $image->width / $width,
                    1 + $image->height / $height
                );
                $im = $im->crop(0, 0, $width, $height);
            }

            $image = $image->composite2($im, "over");
        }

        if (!$fill && !$src) {
            $image = $image->newFromImage([255, 255, 255]);
        }
        return $image;

    }
}
