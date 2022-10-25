<?php

namespace PlayBill\Component;

use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Color;

class Circular extends AbstractComponent implements ComponentInterface
{

    /**
     * @param Image $image
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {
        $fill = $this->options->fill;
        $colors = Color::auto2rgba($fill);
        $overlay = Image::black(
            $this->options->width,
            $this->options->height
        )->multiply([1, 1, 1, 0])
            ->cast('uchar')
            ->draw_circle(255,
                $this->options->width / 2,
                $this->options->height / 2,
                $this->options->width / 2,
                ['fill' => true]
            );

        $red = $image->newFromImage([$colors[0], $colors[1], $colors[2]])
            ->copy(['interpretation' => 'srgb']);
        $overlay = $red->bandjoin($overlay);

        if ($overlay) {
            $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
                ->composite($overlay, "over", [
                    'x' => $this->options->left,
                    'y' => $this->options->top,
                ]);
        }
        return $image;
    }
}
