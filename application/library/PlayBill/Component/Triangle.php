<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interpolate;

class Triangle extends AbstractComponent implements ComponentInterface
{
    /**
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {
        $width = $this->options->width / 2;

        $overlay = Image::svgload_buffer(<<<EOF
<svg width="{$this->options->width}" height="{$this->options->height}">
    <polygon  points="{$width},0 0,{$this->options->height} {$this->options->width},{$this->options->height}" fill="{$this->options->fill}"></polygon>
</svg>
EOF
        )->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ], ['premultiplied' => true]);

        $overlay = $overlay->rotate($this->options->angle);
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
