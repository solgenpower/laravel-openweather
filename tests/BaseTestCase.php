<?php

namespace SolgenPower\LaravelOpenWeather\Test;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use SolgenPower\LaravelOpenWeather\Facades\OpenWeather;
use SolgenPower\LaravelOpenWeather\OpenWeatherServiceProvider;

abstract class BaseTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            OpenWeatherServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'OpenWeather' => OpenWeather::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }
}
