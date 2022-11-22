<?php
namespace PlayBill\Component;


use HttpUtils\Client;
use HttpUtils\Exception;
use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Alpha;

class Pic extends AbstractComponent implements ComponentInterface
{

    /**
     * @param Image $image
     * @param array $changeData
     * @return mixed
     * @throws Exception
     * @throws Vips\Exception
     */
    public function run(Vips\Image $image, array $changeData = []){
        $src = empty($changeData[$this->options->uuid]) ? $this->options->src : $changeData[$this->options->uuid];
        $http = new Client();
        $file_get_contents = $http->get($src);

        $im = Image::newFromBuffer($file_get_contents);

        $im = Alpha::addAlpha($im)->affine([
            $this->options->scaleX , 0, 0, $this->options->scaleY
        ])->rotate($this->options->angle);
        $overlay = $this->opacity($im);
        $image = $this->merge($image, $overlay);
        return $image;
    }
}
