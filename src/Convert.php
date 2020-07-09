<?php

namespace Spatie\Color;

class Convert
{
    public static function hexChannelToRgbChannel(string $hexValue): int
    {
        return hexdec($hexValue);
    }

    public static function rgbChannelToHexChannel(int $rgbValue): string
    {
        return str_pad(dechex($rgbValue), 2, '0', STR_PAD_LEFT);
    }

    public static function hslValueToRgb(float $hue, float $saturation, float $lightness): array
    {
        $c = (1 - abs(2 * ($lightness / 100) - 1)) * ($saturation / 100);
        $x = $c * (1 - abs(fmod($hue / 60, 2) - 1));
        $m = ($lightness / 100) - ($c / 2);

        $h = (360 + ($hue % 360)) % 360;  // hue values can be less than 0 and greater than 360. This normalises them into the range 0-360.

        if ($h > 0 && $h <= 60) {
            return [round(($c + $m) * 255), round(($x + $m) * 255), round($m * 255)];
        }

        if ($h > 60 && $h <= 120) {
            return [round(($x + $m) * 255), round(($c + $m) * 255), round($m * 255)];
        }

        if ($h > 120 && $h <= 180) {
            return [round($m * 255), round(($c + $m) * 255), round(($x + $m) * 255)];
        }

        if ($h > 180 && $h <= 240) {
            return [round($m * 255), round(($x + $m) * 255), round(($c + $m) * 255)];
        }

        if ($h > 240 && $h <= 300) {
            return [round(($x + $m) * 255), round($m * 255), round(($c + $m) * 255)];
        }

        if ($h > 300 && $h <= 360) {
            return [round(($c + $m) * 255), round($m * 255), round(($x + $m) * 255)];
        }
    }

    public static function rgbValueToHsl($red, $green, $blue)
    {
        $r = $red / 255;
        $g = $green / 255;
        $b = $blue / 255;

        $cmax = max($r, $g, $b);
        $cmin = min($r, $g, $b);
        $delta = $cmax - $cmin;

        $hue = 0;
        if ($delta !== 0) {
            if ($r === $cmax) {
                $hue = 60 * fmod(($g - $b) / $delta, 6);
            }

            if ($g === $cmax) {
                $hue = 60 * ((($b - $r) / $delta) + 2);
            }

            if ($b === $cmax) {
                $hue = 60 * ((($r - $g) / $delta) + 4);
            }
        }

        $lightness = ($cmax + $cmin) / 2;

        $saturation = $delta / (1 - abs((2 * $lightness) - 1));

        return [$hue, min($saturation, 1) * 100, min($lightness, 1) * 100];
    }

    public static function toLab(Color $color): Lab
    {

        $r = ($color->red() / 255);    //R from 0 to 255
        $g = ($color->green() / 255);  //G from 0 to 255
        $b = ($color->blue() / 255);   //B from 0 to 255


        //assume sRGB
        $r = $r > 0.04045 ? pow((($r + 0.055) / 1.055), 2.4) : ($r / 12.92);
        $g = $g > 0.04045 ? pow((($g + 0.055) / 1.055), 2.4) : ($g / 12.92);
        $b = $b > 0.04045 ? pow((($b + 0.055) / 1.055), 2.4) : ($b / 12.92);

        //Observer. = 2°, Illuminant = D65
        $x = ($r * 0.4124 + $g * 0.3576 + $b * 0.1805) / 95.047;
        $y = ($r * 0.2126 + $g * 0.7152 + $b * 0.0722) / 100.0;
        $z = ($r * 0.0193 + $g * 0.1192 + $b * 0.9505) / 108.883;

        $x = $x > 0.008856 ? pow($x, 1 / 3) : (7.787 * $x) + (16 / 116);
        $y = $y > 0.008856 ? pow($y, 1 / 3) : (7.787 * $y) + (16 / 116);
        $z = $z > 0.008856 ? pow($z, 1 / 3) : (7.787 * $z) + (16 / 116);

        $l = (116 * $y) - 16;
        $a = 500 * ($x - $y);
        $b = 200 * ($y - $z);

        return new Lab($l, $a, $b);

    }
}
