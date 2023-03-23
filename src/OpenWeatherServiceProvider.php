<?php

namespace SolgenPower\LaravelOpenWeather;

use Illuminate\Support\ServiceProvider;
use SolgenPower\LaravelOpenWeather\Contracts\OpenWeatherAPI;

class OpenWeatherServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/open-weather.php' => config_path('open-weather.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/open-weather.php', 'open-weather');

        $this->app->bind(
            'laravel-openweather',
            fn () => new OpenWeather(config('open-weather'))
        );

        $this->app->bind(
            OpenWeatherAPI::class,
            fn () => new OpenWeather(config('open-weather'))
        );
    }
}
