<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;

class Triangle extends AbstractComponent implements ComponentInterface
{
    /**
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {

        var_dump($this->options);exit();

//        $draw = new \ImagickDraw();
//
//        $fillColor = new \ImagickPixel($this->options->fill);
//        $draw->setFillColor($fillColor);
//
//        $points = [
//            [$this->options->width /2, 0],
//            [0,  $this->options->height],
//            [$this->options->width, $this->options->top],
//        ];
//
//        $draw->polygon($points);
//
//        $imagick = new \Imagick();
//        $imagick->newImage($this->options->width, $this->options->height);
//        $imagick->setImageFormat("png");
//        $imagick->drawImage($draw);
//        $imageBlob = $imagick->getImageBlob();

        $overlay = Image::magickload_buffer($imageBlob);
        $image->copy(['interpretation' => Vips\Interpretation::SRGB])
            ->composite($overlay, "over", [
                'x' => $this->options->left,
                'y' => $this->options->top,
            ]);

        return $image;
    }
}
