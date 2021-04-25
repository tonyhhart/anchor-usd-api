<?php

use League\ColorExtractor\Color;

if (!function_exists('dominant_color')) {
    /**
     * @param $path
     * @return mixed
     */
    function dominant_color($path)
    {
        if (!$path) return null;

        $image = imagecreatefrompng($path);

        if (!$image) return null;

        $areColorsIndexed = !imageistruecolor($image);
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        $backgroundColor = null;
        $colors = [];

        $backgroundColorRed = ($backgroundColor >> 16) & 0xFF;
        $backgroundColorGreen = ($backgroundColor >> 8) & 0xFF;
        $backgroundColorBlue = $backgroundColor & 0xFF;

        for ($x = 0; $x < $imageWidth; ++$x) {
            for ($y = 0; $y < $imageHeight; ++$y) {
                $color = imagecolorat($image, $x, $y);
                if ($areColorsIndexed) {
                    $colorComponents = imagecolorsforindex($image, $color);
                    $color = ($colorComponents['alpha'] * 16777216) +
                        ($colorComponents['red'] * 65536) +
                        ($colorComponents['green'] * 256) +
                        ($colorComponents['blue']);
                }

                if ($alpha = $color >> 24) {
                    if ($backgroundColor === null) {
                        continue;
                    }

                    $alpha /= 127;
                    $color = (int)(($color >> 16 & 0xFF) * (1 - $alpha) + $backgroundColorRed * $alpha) * 65536 +
                        (int)(($color >> 8 & 0xFF) * (1 - $alpha) + $backgroundColorGreen * $alpha) * 256 +
                        (int)(($color & 0xFF) * (1 - $alpha) + $backgroundColorBlue * $alpha);
                }

                isset($colors[$color]) ?
                    $colors[$color] += 1 :
                    $colors[$color] = 1;
            }
        }

        arsort($colors);

        return Color::fromIntToHex(collect($colors)->keys()->first());
    }
}


if (!function_exists('current_user')) {
    /**
     * @return \App\Models\User
     */
    function current_user()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }
}
