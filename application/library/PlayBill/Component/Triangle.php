<?php
namespace PlayBill\Component;


use Jcupitt\Vips;

class Triangle implements ComponentInterface
{
    /**
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(){
        $text = Vips\Image::text('Hello world!', [
            'font' => 'sans 30',
            'width' => $this->options->width
        ]);
        $red = $text->newFromImage([255, 0, 0])->copy(['interpretation' => 'srgb']);
        return $red->bandjoin($text);
    }
}
