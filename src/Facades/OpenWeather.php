<?php

namespace SolgenPower\LaravelOpenweather\Facades;

use Illuminate\Support\Facades\Facade;

class OpenWeather extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-openweather';
    }
}
