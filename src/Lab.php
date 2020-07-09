<?php

namespace Spatie\Color;

class Lab implements Color
{
    /** @var float */
    protected $l, $a, $b;

    public function __construct(float $l, float $a, float $b)
    {
        $this->l = $l;
        $this->a = $a;
        $this->b = $b;
    }

    public static function fromString(string $string): Lab
    {
        $matches = null;
        preg_match('/lab\(*([-+]?[0-9]*\.?[0-9]) *,([-+]?[0-9]*\.?[0-9]*) *, *([-+]?[0-9]*\.?[0-9]*)\)/i', $string, $matches);

        return new Lab($matches[1], $matches[2], $matches[3]);
    }

    public function l()
    {
        return $this->l;
    }

    public function a()
    {
        return $this->a;
    }

    public function b()
    {
        return $this->b;
    }

    public function red()
    {
        return $this->toRgb()->red();
    }

    public function green()
    {
        return $this->toRgb()->blue();

    }

    public function blue()
    {
        return $this->toRgb()->blue();
    }

    public function toHex(): Hex
    {
        return $this->toRgb()->toHex();
    }

    public function toHsl(): Hsl
    {
        return $this->toRgb()->toHsl();
    }

    public function toHsla(float $alpha = 1): Hsla
    {
        return $this->toRgb()->toHsla($alpha);
    }

    public function toRgb(): Rgb
    {
        $from = $this->toXyz();

        $x = $from[0] / 100; //X from 0 to self::REF_X
        $y = $from[1] / 100; //Y from 0 to self::REF_Y
        $z = $from[2] / 100; //Z from 0 to self::REF_Z

        //Observer = 2°, Illuminant = D65
        $r = $x * 3.2406 + $y * -1.5372 + $z * -0.4986;
        $g = $x * -0.9689 + $y * 1.8758 + $z * 0.0415;
        $b = $x * 0.0557 + $y * -0.2040 + $z * 1.0570;

        //Assume sRGB
        $r = $r > 0.0031308 ? ((1.055 * pow($r, 1.0 / 2.4)) - 0.055) : $r * 12.92;
        $g = $g > 0.0031308 ? ((1.055 * pow($g, 1.0 / 2.4)) - 0.055) : $g * 12.92;
        $b = $b > 0.0031308 ? ((1.055 * pow($b, 1.0 / 2.4)) - 0.055) : $b * 12.92;

        return new Rgb(
            round($r * 255, 0, PHP_ROUND_HALF_UP),
            round($g * 255, 0, PHP_ROUND_HALF_UP),
            round($b * 255, 0, PHP_ROUND_HALF_UP)
        );
    }

    public function toRgba(float $alpha = 1): Rgba
    {
        return $this->toRgb()->toRgba($alpha);
    }

    public function toXyz()
    {
        $y = ($this->l + 16) / 116;
        $x = $this->a / 500 + $y;
        $z = $y - $this->b / 200;

        $y2 = pow($y, 3);
        $x2 = pow($x, 3);
        $z2 = pow($z, 3);

        $y = $y2 > 0.008856 ? $y2 : ($y - 16 / 116) / 7.787;
        $x = $x2 > 0.008856 ? $x2 : ($x - 16 / 116) / 7.787;
        $z = $z2 > 0.008856 ? $z2 : ($z - 16 / 116) / 7.787;

        return [$x * 95.047, $y * 100.0, $z * 108.883];
    }

    public function __toString(): string
    {
        $l = round($this->l, 4);
        $a = round($this->a, 4);
        $b = round($this->b, 4);

        return "lab($l,$a,$b)";
    }
}
