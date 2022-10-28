<?php


namespace PlayBill\Component;




class AbstractComponent
{
    protected \stdClass $options;

    public function __construct($options)
    {

        $this->options = $options;
    }


    public function minXY($image)
    {
        $minx = $image->get('width');
        $miny = $image->get('height');
        foreach ($this->options->aCoords as $value) {
            $minx = min($minx, $value->x);
            $miny = min($miny, $value->y);
        }
        return ['x' => $minx, 'y' => $miny];
    }

    /**
     * 設置透明度
     * @param $overlay
     * @return \Jcupitt\Vips\Image
     * @throws \Jcupitt\Vips\Exception
     */
    public function opacity($overlay){
        if ($overlay && $this->options->opacity != 1) {
            $overlay = Alpha::addAlpha($overlay);
            $overlay = $overlay->multiply([1, 1, 1, $this->options->opacity])->cast("uchar");
        }
        return $overlay;
    }

    public function merge($image, $overlay){
        if ($overlay) {
            $minXY = self::minXY($image);
            $image = $image->copy(['interpretation' =>  \Jcupitt\Vips\Interpretation::SRGB])
                ->composite($overlay, "over", [
                    'x' => $minXY['x'],
                    'y' => $minXY['y'],
                ]);
        }
        return $image;
    }

}
