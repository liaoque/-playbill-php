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
        )->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);

        $overlay = $this->opacity($overlay);
        $image =  $this->merge($image, $overlay);
        return $image;

    }
}
