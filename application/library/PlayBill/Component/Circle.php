<?php

namespace PlayBill\Component;

use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Color;

class Circle extends AbstractComponent implements ComponentInterface
{

    /**
     * @param Pic $image
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {

        $w = $this->options->width / 2;
        $h = $this->options->height / 2;
        $overlay = Image::svgload_buffer(<<<EOF
<svg viewBox="0 0 {$this->options->width} {$this->options->height}">
    <circle  cx="{$w}" cy="{$h}" r="{$w}" fill="{$this->options->fill}" 
    ></circle>
</svg>
EOF
        );
        $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
            ->composite($overlay, "over", [
            'x' => $this->options->left,
            'y' => $this->options->top,
        ]);
        return $image;
//
//        $fill = $this->options->fill;
//        $colors = Color::auto2rgba($fill);
//        $overlay = Image::black(
//            $this->options->width,
//            $this->options->height
//        )->multiply([1, 1, 1, 0])
//            ->cast('uchar')
//            ->draw_circle(255,
//                $this->options->width / 2,
//                $this->options->height / 2,
//                $this->options->width / 2,
//                ['fill' => true]
//            );
//
//        $red = $image->newFromImage([$colors[0], $colors[1], $colors[2]])
//            ->copy(['interpretation' => 'srgb']);
//        $overlay = $red->bandjoin($overlay);
//
//        if ($overlay) {
//            $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
//                ->composite($overlay, "over", [
//                    'x' => $this->options->left,
//                    'y' => $this->options->top,
//                ]);
//        }
//        return $image;
    }
}
