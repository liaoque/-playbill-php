<?php


namespace PlayBill\Component;


use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Image;
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
//            $im->

            $image = $image->composite($im, "over", [
                'x' => 0, 'y' => 0
            ]);
//            Image::black(375, 667);
//            $image = $image->newFromImage([$colors[0], $colors[1], $colors[2]]);
        }
        return $image;

    }
}
