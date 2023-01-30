<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Alpha;
use PlayBill\Utils\Color;

class Polygon extends AbstractComponent implements ComponentInterface
{
    use Template;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setTemplate(<<<EOF
<svg viewBox="0 0 {width} {height}">
    <polygon  points="{points}" stroke="{stroke}" fill="{fill}" stroke-width="{stroke_width}"></polygon>
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
        $xArray = array_map(function ($item) {
            return $item->x;
        }, $this->options->points);
        $minx = min(...$xArray);
        $leftX = abs(min($minx, 0));

        $yArray = array_map(function ($item) {
            return $item->y;
        }, $this->options->points);
        $miny = min(...$yArray);
        $topY = abs(min($miny, 0));

        $options = implode(" ", array_map(function ($item) use ($leftX, $topY) {
            $x = $item->x + $leftX;
            $y = $item->y + $topY;
            return "{$x},{$y}";
        }, $this->options->points));

        $width = $this->options->width + max($minx, 0);
        $height = $this->options->height + max($miny, 0);

        $data = [
            '{width}' => $width + $this->options->strokeWidth,
            '{height}' => $height + $this->options->strokeWidth,
            '{fill}' => $this->options->fill,
            '{stroke}' => $this->options->stroke,
            '{stroke_width}' => $this->options->strokeWidth,
            '{points}' => $options,
        ];
//        var_dump($data);
        $render = self::render($data);
        $overlay = Image::svgload_buffer($render)->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);

        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay);
        return $image;

    }
}
