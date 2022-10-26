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
     * @param Pic $image
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {

        $overlay = Image::svgload_buffer(<<<EOF
<svg viewBox="0 0 {$this->options->width} {$this->options->height}">
    <rect x="0" y="0" 
    height="{$this->options->width}" 
    width="{$this->options->width}" 
    fill="{$this->options->fill}" 
    ></rect>
</svg>
EOF
        )->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);

        $width = $overlay->get('width') / 2;
        $height = $overlay->get('height') / 2;
        $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
            ->composite($overlay, "over", [
                'x' => $this->options->left - ($width - ($width * cos($this->options->angle) +
                            $height * sin($this->options->angle))),
                'y' => $this->options->top + ($height - ($width * sin($this->options->angle) +
                            $height * cos($this->options->angle))),
        ]);
        return $image;

    }
}
