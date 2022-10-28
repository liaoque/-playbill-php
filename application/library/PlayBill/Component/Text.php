<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Color;

class Text extends AbstractComponent implements ComponentInterface
{
    /**
     * @param Image $image
     * @return Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {
        $context = $this->options->text;
        $fill = $this->options->fill;

        $text = Image::text($context, [
            'width' => $this->options->width,
            'height' => $this->options->height,
            'font' => 'observer-test3',
            'fontfile' => \Yaf_Dispatcher::getInstance()->getApplication()->getAppDirectory() . '/../public/fonts/sourcesanspro-regular.woff',
        ])->rotate($this->options->angle);

        $colors = Color::auto2rgba($fill);
        $red = $text->newFromImage([$colors[0], $colors[1], $colors[2]])
            ->copy(['interpretation' => 'srgb']);
        $overlay = $red->bandjoin($text);

        if ($overlay) {
            $minXY = self::minXY($image);
            $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
                ->composite($overlay, "over", [
                    'x' => $minXY['x'],
                    'y' => $minXY['y'],
                ]);
        }
        return $image;
    }


    public function degreesToRadians($degrees)
    {
        return $degrees * pi() / 180;
    }
}
