<?php

namespace SolgenPower\LaravelOpenWeather\Contracts;

use SolgenPower\LaravelOpenWeather\DataTransferObjects\Weather;
use SolgenPower\LaravelOpenWeather\Enums\TemperatureUnit;

interface OpenWeatherAPI
{
    public function asTemperatureUnit(TemperatureUnit $temperatureUnit): self;

    public function coordinates(string $latitude, string $longitude): Weather;

    public function zip(string $zip, string $countryCode): Weather;

    public function city(string $city, string $stateCode = '', string $countryCode = ''): Weather;
}
