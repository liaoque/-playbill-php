<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interpolate;
use PlayBill\Utils\Alpha;

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
        ], ['premultiplied' => true])
            ->rotate($this->options->angle);

        $overlay = $this->opacity($overlay);
        $image =  $this->merge($image, $overlay);

        return $image;
    }
}
