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
     * @param array $changeData
     * @return Image
     * @throws Vips\Exception
     * @throws \Yaf_Exception
     * @throws \Yaf_Exception_TypeError
     */
    public function run(Image $image, array $changeData = [])
    {
        $context = empty($changeData[$this->options->uuid]) ? $this->options->text : urldecode($changeData[$this->options->uuid]);
        $fill = $this->options->fill;
        $fontFamily = $this->options->fontFamily;
        $yafConfigIni = Config::get('fonts');
        $fontFamilyFile = $yafConfigIni->get('fonts.' . $fontFamily);
        if ($fontFamilyFile->get('file')) {
            $fontFamilyFile = Config::rootPath($fontFamilyFile->get('file'));
        } elseif ($fontFamilyFile->get('url')) {
            $fontFamilyFile = $fontFamilyFile->get('url');
        } else {
            throw new  \Yaf_Exception('字体不存在', AppResponsePlayBill::FONT_NOT_FOUND);
        }

        $radius = intval($this->options->strokeWidth * $this->options->scaleX);
        $text = Image::text($context, [
            'width' => $this->options->width * $this->options->scaleX,
            'height' => $this->options->height * $this->options->scaleY,
            'font' => $fontFamily,
            'fontfile' => $fontFamilyFile,
        ]);

        $colors = Color::auto2rgba($fill);
        $overlay = $text->newFromImage([$colors[0], $colors[1], $colors[2]])->bandjoin($text)->copy([
            'interpretation' => 'srgb',
        ]);

//        circle_mask = pyvips.Image.black(radius * 2 + 1, radius * 2 + 1) \
//    .add(128) \
//    .draw_circle(255, radius, radius, radius, fill=True)

//        https://github.com/libvips/libvips/discussions/2123
        $bgTextMask = $text->embed($radius, $radius, $this->options->width + 2 * $radius, $this->options->height + 2 * $radius);
        $bgTextMask = $bgTextMask->gaussmat($radius / 2, 0.1, ['separable' => true])->multiply(3*$radius);
        $bgText = $text->convsep($bgTextMask)->cast("uchar");
        $colors = Color::auto2rgba($this->options->stroke);
        $overlayBg = $bgText->newFromImage([$colors[0], $colors[1], $colors[2]])->bandjoin($bgText)->copy(['interpretation' => 'srgb']);
        $overlay = $overlayBg->composite($overlay, 'over')->rotate($this->options->angle);


        $overlay = $this->opacity($overlay);
        $image = $this->merge($image, $overlay);
        return $image;
    }


    public function degreesToRadians($degrees)
    {
        return $degrees * pi() / 180;
    }
}
