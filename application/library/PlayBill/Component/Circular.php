<?php
namespace PlayBill\Component;

use Jcupitt\Vips;
use Jcupitt\Vips\Image;

class Circular implements ComponentInterface
{

    /**
     * @param Image $image
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image){
        $text = Vips\Image::text('Hello world!', [
            'font' => 'sans 30',
            'width' => $this->options->width
        ]);
        $red = $text->newFromImage([255, 0, 0])->copy(['interpretation' => 'srgb']);
        return $red->bandjoin($text);
    }
}
