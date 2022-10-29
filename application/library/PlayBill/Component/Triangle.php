<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interpolate;
use PlayBill\Utils\Alpha;

class Triangle extends AbstractComponent implements ComponentInterface
{


    use Template;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setTemplate(<<<EOF
<svg viewBox="0 0 {width} {height}">
    <polygon  points="{w},0 0,{h} {w},{h}" stroke="{stroke}" fill="{fill}" stroke-width="{stroke_width}"></polygon>
</svg>
EOF
        );
    }


    /**
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {

        $data = [
            '{width}' => $this->options->width + $this->options->strokeWidth,
            '{height}' => $this->options->height + $this->options->strokeWidth,
            '{w}' => $this->options->width,
            '{h}' => $this->options->height,
            '{fill}' => $this->options->fill,
            '{stroke}' => $this->options->stroke,
            '{stroke_width}' => $this->options->strokeWidth,
        ];
        $render = self::render($data);

        $overlay = Image::svgload_buffer($render)->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ], ['premultiplied' => true])
            ->rotate($this->options->angle);

        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay);

        return $image;
    }
}
