<?php

namespace PlayBill\Component;

use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Color;

class Circle extends AbstractComponent implements ComponentInterface
{
    use Template;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setTemplate(<<<EOF
<svg viewBox="0 0 {width} {height}">
    <circle  cx="{cx}" cy="{cy}" r="{r}" fill="{fill}" 
    stroke="{stroke}" stroke-width="{stroke_width}"
    ></circle>
</svg>
EOF
        );
    }


    /**
     * @param Pic $image
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {
        $data = [
            '{width}' => $this->options->width + $this->options->strokeWidth ,
            '{height}' => $this->options->height + $this->options->strokeWidth ,
            '{cx}' => $this->options->width / 2,
            '{cy}' => $this->options->height / 2,
            '{r}' => $this->options->width / 2,
            '{fill}' => $this->options->fill,
            '{stroke}' => $this->options->stroke,
            '{stroke_width}' => $this->options->strokeWidth,
        ];
        $render = self::render($data);
        $overlay = Image::svgload_buffer($render)->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);

//        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay);
        return $image;

    }
}
