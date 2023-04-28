<?php

namespace SolgenPower\LaravelOpenWeather\Test;

use PHPUnit\Framework\TestCase;
use SolgenPower\LaravelOpenWeather\Enums\TemperatureUnit;

class TemperatureUnitTest extends TestCase
{
    /** @test */
    public function it_returns_temperature_symbol()
    {
        $this->assertEquals('°K', TemperatureUnit::Standard->symbol());
        $this->assertEquals('°F', TemperatureUnit::Imperial->symbol());
        $this->assertEquals('°C', TemperatureUnit::Metric->symbol());
    }

    /** @test */
    public function it_returns_temperature_unit()
    {
        $this->assertEquals('Kelvin', TemperatureUnit::Standard->unit());
        $this->assertEquals('Fahrenheit', TemperatureUnit::Imperial->unit());
        $this->assertEquals('Celsius', TemperatureUnit::Metric->unit());
    }
}
