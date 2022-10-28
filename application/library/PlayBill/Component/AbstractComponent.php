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
}
