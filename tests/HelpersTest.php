<?php

namespace SolgenPower\LaravelOpenWeather\Test;

class HelpersTest extends BaseTestCase
{
    /** @test */
    public function it_converts_degrees_to_cardinal_direction()
    {
        $this->assertEquals(degreesToCardinal(0), 'N');
        $this->assertEquals(degreesToCardinal(45), 'NE');
        $this->assertEquals(degreesToCardinal(90), 'E');
        $this->assertEquals(degreesToCardinal(135), 'SE');
        $this->assertEquals(degreesToCardinal(180), 'S');
        $this->assertEquals(degreesToCardinal(225), 'SW');
        $this->assertEquals(degreesToCardinal(270), 'W');
        $this->assertEquals(degreesToCardinal(315), 'NW');
        $this->assertEquals(degreesToCardinal(360), 'N');
    }

    /** @test */
    public function it_gets_the_full_path_for_the_weather_condition_icon()
    {
        $iconPath = iconCodeToUrl('09d');

        $this->assertEquals($iconPath, 'https://openweathermap.org/img/wn/09d.png');
    }
}
