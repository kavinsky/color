<?php

namespace Spatie\Color\Test;

use PHPUnit\Framework\TestCase;
use Spatie\Color\Hex;
use Spatie\Color\Hsl;
use Spatie\Color\Hsla;
use Spatie\Color\Lab;
use Spatie\Color\Rgb;
use Spatie\Color\Rgba;

class LabTest extends TestCase
{
    /** @test */
    public function it_is_initializable()
    {
        $lab = new Lab(100, 0.005, -0.010);

        $this->assertInstanceOf(Lab::class, $lab);
        $this->assertEquals(100, $lab->l());
        $this->assertEquals(0.005, $lab->a());
        $this->assertEquals(-0.010, $lab->b());
    }

    /** @test */
    public function it_can_be_created_from_a_string()
    {
        $lab = Lab::fromString('lab(100,0.005,-0.010)');

        $this->assertInstanceOf(Lab::class, $lab);
        $this->assertEquals(100.0, $lab->l());
        $this->assertEquals(0.005, $lab->a());
        $this->assertEquals(-0.010, $lab->b());
    }

    /** @test */
    public function it_can_be_converted_to_rgb()
    {
        $rgb = Lab::fromString('lab(34,0.5,-0.2)')->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(round(80.62), $rgb->red());
        $this->assertEquals(round(79.69), $rgb->green());
        $this->assertEquals(round(80.25), $rgb->blue());
    }

    /** @test */
    public function it_can_be_converted_to_rgba()
    {
        $rgba = Lab::fromString('lab(34,0.5,-0.2)')->toRgba(.5);

        $this->assertInstanceOf(Rgba::class, $rgba);
        $this->assertEquals(round(80.62), $rgba->red());
        $this->assertEquals(round(79.69), $rgba->green());
        $this->assertEquals(round(80.25), $rgba->blue());
        $this->assertEquals(0.5, $rgba->alpha());
    }

    /** @test */
    public function it_shows_red_green_blue_correctly()
    {
        $lab = Lab::fromString('lab(34,0.5,-0.2)');

        $this->assertInstanceOf(Lab::class, $lab);
        $this->assertEquals(round(80.62), $lab->red());
        $this->assertEquals(round(79.69), $lab->green());
        $this->assertEquals(round(80.25), $lab->blue());
    }

    /** @test */
    public function it_can_be_converted_to_hex()
    {
        // reference: https://www.nixsensor.com/free-color-converter/
        // Illuminant and reference angle: D65 2º
        $hex = Lab::fromString('lab(50,5,5)')->toHex();

        $this->assertInstanceOf(Hex::class, $hex);
        $this->assertEquals('#83746f', (string) $hex);
    }

    /** @test */
    public function it_can_be_converted_to_hsl()
    {
        $hsl = Lab::fromString('lab(50,5,5)')->toHsl();

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertEquals('hsl(15,8%,47%)', (string) $hsl);
    }

    /** @test */
    public function it_can_be_converted_to_hsla()
    {
        $hsla = Lab::fromString('lab(50,5,5)')->toHsla(0.5);

        $this->assertInstanceOf(Hsla::class, $hsla);
        $this->assertEquals('hsla(15,8%,47%,0.5)', (string) $hsla);
    }

    /** @test */
    public function it_can_be_converted_to_string()
    {
        $lab = new Lab(35, 5, 0);

        $this->assertEquals('lab(35,5,0)', (string) $lab);
    }
}
