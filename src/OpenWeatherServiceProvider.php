<?php

namespace SolgenPower\LaravelOpenweather;

use Illuminate\Support\ServiceProvider;
use SolgenPower\LaravelOpenweather\Contracts\OpenWeatherAPI;

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

        $this->app->singleton(
            'laravel-openweather',
            fn () => new OpenWeather(config('open-weather'))
        );

        $this->app->singleton(
            OpenWeatherAPI::class,
            fn () => new OpenWeather(config('open-weather'))
        );
    }
}
