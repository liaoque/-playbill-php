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
        // 坐标会有负数的情况， 所以要对宽高进行处理
        $xArray = array_map(function ($item) {
            return $item->x;
        }, $this->options->points);
        $leftX = abs(min(min(...$xArray), 0));
        $rightX = max(max(...$xArray), $this->options->width);

        $yArray = array_map(function ($item) {
            return $item->y;
        }, $this->options->points);
        $topY = abs(min(min(...$yArray), 0));
        $downY = max(max(...$yArray), $this->options->height);

        $options = implode(" ", array_map(function ($item) use ($leftX, $topY) {
            $x = $item->x + $leftX;
            $y = $item->y + $topY;
            return "{$x},{$y}";
        }, $this->options->points));

//        var_dump([
//            $leftX, $rightX,
//            $topY, $downY
//        ], $options);
        $data = [
            '{width}' => $leftX + $rightX,
            '{height}' => $topY + $downY,
            '{fill}' => $this->options->fill,
            '{stroke}' => $this->options->stroke,
            '{stroke_width}' => $this->options->strokeWidth,
            '{points}' => $options,
        ];
        $render = self::render($data);
        $overlay = Image::svgload_buffer($render)->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);

        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay, [
            'left' => $leftX,
            'top' => $topY
        ]);
        return $image;

    }
}
