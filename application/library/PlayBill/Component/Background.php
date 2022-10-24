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

class Background extends AbstractComponent implements ComponentInterface
{

    /**
     * @param Image $image
     * @return Image
     * @throws Exception
     */
    public function run(Image $image)
    {
        $fill = $this->options->background;
//         TODO: Implement run() method.

        $colors = Color::auto2rgba($fill);
        $image = $image->newFromImage([$colors[0], $colors[1], $colors[2]]);

        return $image;
    }
}
