<?php

namespace SolgenPower\LaravelOpenweather\Contracts;

use SolgenPower\LaravelOpenweather\DataTransferObjects\Weather;
use SolgenPower\LaravelOpenweather\Enums\TemperatureUnit;

interface OpenWeatherAPI
{
    public function asTemperatureUnit(TemperatureUnit $TemperatureUnit): self;

    public function coordinates(string $latitude, string $longitude): Weather;

    public function zip(string $zip, string $countryCode): Weather;

    public function city(string $city, string $stateCode = '', string $countryCode = ''): Weather;
}
