
nodejs https://sharp.pixelplumbing.com/install

python https://www.aiuai.cn/aifarm1738.html
        https://libvips.github.io/pyvips/README.html#how-it-works

php https://libvips.github.io/php-vips/classes/Jcupitt-Vips-Image.html



```
创建lut
$lut = Vips\Image::identity(['ushort' => $ushort]);
```

```
除法
  $lut = $lut->divide($range);
```

```
翻转
$image = $image->invert();
```

```
卷积运算
$image = Vips\Image::newFromArray([[ 255, 255, 0], [0,127,0]]);
$im = $im->conv($mask);
```

```
缩小
$im = $im->reduce(1.0 / 0.5, 1.0 / 0.5, ['kernel' => Vips\Kernel::LINEAR]);
```

```
线性变换
$image = $image->linear(160, 3);
```

```
// 透明图
$watermark = $watermark->multiply([1, 1, 1, 0.3])->cast("uchar");
```

```
replicate 复制图片, 参考css的 replicate 属性
$watermark = $watermark->replicate(
    1 + $image->width / $watermark->width,
    1 + $image->height / $watermark->height
);
```

```
Copy an image.
$overlay = $overlay->copy(['interpretation' => 'srgb']);
```

```
裁切
$watermark = $watermark->crop(0, 0, $image->width, $image->height);
```

```
不知道啥意思
 $result = $result->cast($ushort ? Vips\BandFormat::USHORT : Vips\BandFormat::UCHAR);
```

```
水平缩小图像。
    $lut = $lut->shrinkh(2);
```

```
转换颜色空间
    $oldInterpretation = $image->interpretation;
  $image->colourspace($oldInterpretation, [
        'source_space' => Vips\Interpretation::LABS
    ]);
```

```
文字
$text_mask = Vips\Image::text($text, [
  'width' => $image->width,
  'dpi' => 150
]);
```

```
如果 $then, 否则 $else
$overlay = $text_mask->ifthenelse($then, $else, [
  'blend' => true
]);
```

```
将图像阵列与混合模式阵列混合。
$image = $image->composite($overlay, 'over');

Image composite(Image[]|Image $in, integer[]|integer $mode, array $options = [])
```

```
// 使用混合模式混合一对图像。
$image = $image->composite2($watermark, 'over');

Image composite2(Image $overlay, string $mode, array $options = [])
```

```
将图像嵌入到较大的图像中。
$overlay = $overlay->embed(
    $margin,
    $page_height - $overlay->height - $margin,
    $image->width,
    $page_height
);

// 类似css 的margin
// x和y表示坐标
// $width， $height 表示生成新图片的宽高
Image embed(integer $x, integer $y, integer $width, integer $height, array $options = [])
```

```
rotate

```


```
Image::svgload_buffer("
<svg viewBox="0 0 100 100">
<circle r="15" cx="50" cy="18" fill="#900"/>
</svg>
")
```
