<?php

namespace PlayBill\Component;


use AppResponse\AppResponsePlayBill;
use AppUtils\Config;
use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Alpha;
use PlayBill\Utils\Color;

class Text extends AbstractComponent implements ComponentInterface
{
    /**
     * @param Image $image
     * @return Image
     * @throws Vips\Exception
     * @throws \Yaf_Exception_TypeError|\Yaf_Exception
     */
    public function run(Image $image)
    {
        $context = $this->options->text;
        $fill = $this->options->fill;
        $fontFamily = $this->options->fontFamily;

        $yafConfigIni = Config::get('fonts');
        $fontFamilyFile = $yafConfigIni->get($fontFamily);

        if ($fontFamilyFile->url) {
            $fontFamilyFile = $fontFamilyFile->url;
        } elseif ($fontFamilyFile->file) {
            $fontFamilyFile = Config::rootPath($fontFamilyFile);
        } else {
            throw new  \Yaf_Exception('字体不存在', AppResponsePlayBill::FONT_NOT_FOUND);
        }

        $text = Image::text($context, [
            'width' => $this->options->width,
            'height' => $this->options->height,
            'font' => $fontFamily,
            'fontfile' => $fontFamilyFile,
        ])->rotate($this->options->angle)->affine([
            $this->options->scaleX, 0, 0, $this->options->scaleY
        ]);

        $colors = Color::auto2rgba($fill);
        $red = $text->newFromImage([$colors[0], $colors[1], $colors[2]])
            ->copy(['interpretation' => 'srgb']);
        $overlay = $red->bandjoin($text);


        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay);
        return $image;
    }


    public function degreesToRadians($degrees)
    {
        return $degrees * pi() / 180;
    }
}
