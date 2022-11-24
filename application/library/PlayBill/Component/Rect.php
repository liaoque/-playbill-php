<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Alpha;
use PlayBill\Utils\Color;

class Rect extends AbstractComponent implements ComponentInterface
{
    use Template;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setTemplate(<<<EOF
<svg viewBox="0 0 {width} {height}">
    <rect x="0" y="0" 
    height="{height}" 
    width="{width}" 
    fill="{fill}"  
    stroke="{stroke}" 
    stroke-width="{stroke_width}"
    ></rect>
</svg>
EOF
        );
    }


    /**
     * @param Image $image
     * @param array $changeData
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image, array $changeData = [])
    {

        $data = [
            '{width}' => $this->options->width + $this->options->strokeWidth ,
            '{height}' => $this->options->height + $this->options->strokeWidth ,
            '{fill}' => $this->options->fill,
            '{stroke}' => $this->options->stroke,
            '{stroke_width}' => $this->options->strokeWidth,
        ];
        $render = self::render($data);

        $overlay = Image::svgload_buffer($render)->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);

        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay);
        return $image;

    }
}
