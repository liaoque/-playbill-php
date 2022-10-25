<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Alpha;
use PlayBill\Utils\Color;

class Rect extends AbstractComponent implements ComponentInterface
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
        )->newFromImage([
            $colors[0], $colors[1], $colors[2]
        ])->rotate($this->options->angle);

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
