<?php

namespace SolgenPower\LaravelOpenWeather\Enums;

enum TemperatureUnit: string
{
    case Standard = 'standard';
    case Imperial = 'imperial';
    case Metric = 'metric';

    public function symbol(): string
    {
        return match ($this) {
            self::Standard => '°K',
            self::Imperial => '°F',
            self::Metric => '°C',
        };
    }

    public function unit(): string
    {
        return match ($this) {
            self::Standard => 'Kelvin',
            self::Imperial => 'Fahrenheit',
            self::Metric => 'Celsius',
        };
    }
}
