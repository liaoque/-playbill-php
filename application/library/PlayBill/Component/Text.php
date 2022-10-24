<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Color;

class Text extends AbstractComponent implements ComponentInterface
{
    /**
     * @return Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {
        $context = $this->options->text;
        $fill = $this->options->fill;
        $text2 = Image::text($context);
        var_dump($text2);
        exit();

        $colors = Color::auto2rgba($fill);
        $red = $text->newFromImage([$colors[0], $colors[1], $colors[2]])
            ->copy(['interpretation' => 'srgb']);
        return $red->bandjoin($text);
    }
}
