<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Color;

class Text extends AbstractComponent implements ComponentInterface
{
    /**
     * @param Image $image
     * @return Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {
        $context = $this->options->text;
        $fill = $this->options->fill;
        $this->options->angle = 47;

        $text = Image::text($context, [
            'width' => $this->options->width,
            'height' => $this->options->height,
        ])->rotate($this->options->angle);

        $colors = Color::auto2rgba($fill);
        $red = $text->newFromImage([$colors[0], $colors[1], $colors[2]])
            ->copy(['interpretation' => 'srgb']);
        $overlay = $red->bandjoin($text);

        if ($overlay) {
            $width = $overlay->get('width') / 2;
            $height = $overlay->get('height') / 2;
            var_dump($overlay->get('width'), $image->get('height'));
            $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
                ->composite($overlay, "over", [
                    'x' => 0,
                    'y' => 0,
                ]);
        }
        return $image;
    }
}
