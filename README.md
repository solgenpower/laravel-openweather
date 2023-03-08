# Laravel OpenWeather
A Laravel SDK for OpenWeather API


## Installation

Install the package via composer:
```php 
composer require solgenpower/laravel-openweather
```

If you're interested in modifying the config file, then publish it using the following command:
```php 
php artisan vendor:publish --provider="Solgenpower\LaravelOpenweather\OpenWeatherServiceProvider"
```

This is the contents of the published config file:
```php
return [

    /**
     * API Key for Open Weather
     */
    'api-key' => env('LAREVEL_OPENWEATHER_API_KEY', ''),

    /**
     * API Endpoint for the Current Weather
     */
    'api-current-endpoint' => env('LAREVEL_OPENWEATHER_API_ENDPOINT', 'https://api.openweathermap.org/data/2.5/'),

    /**
     * Endpoint for the Weather Condition icons
     */
    'icon-endpoint' => env('LAREVEL_OPENWEATHER_ICON_URL', 'https://openweathermap.org/img/wn/'),

    'icon-map' => [
        /**
         * Day Icons
         */
        '01d' => '01d.png',
        '02d' => '02d.png',
        '03d' => '03d.png',
        '04d' => '04d.png',
        '09d' => '09d.png',
        '10d' => '10d.png',
        '11d' => '11d.png',
        '13d' => '13d.png',
        '50d' => '50d.png',

        /**
         * Night Icns
         */
        '01n' => '01n.png',
        '02n' => '02n.png',
        '03n' => '03n.png',
        '04n' => '04n.png',
        '09n' => '09n.png',
        '10n' => '10n.png',
        '11n' => '11n.png',
        '13n' => '13n.png',
        '50n' => '50n.png',
    ],

    /**
     * Cache duration, 60*10 is 10 minutes
     */
    'cache-duration' => 60 * 10,

    /**
     * standard => Kelvin
     * imperial => Fahrenheit
     * metric => Celsius
     */
    'units' => 'metric',

];
```

## Usage
You can get weather information by providing coordinates
```php 
$whiteHouseWeather = OpenWeather::coordinates("38.897957", "-77.036560");
echo $whiteHouseWeather->humidity; //64
```
or by zip code
```php 
$californiaWeather = $OpenWeather::zip('90210', 'US');
echo $californiaWeather->windDiretion; //N
```
or by city name
```php
$pheonixWeather = OpenWeather::city('Tucson', 'AZ', 'US');
echo $pheonixWeather->feelsLike; //281.55
```

All these methods will return a Weather DTO that looks like this:
```php
class Weather
{
    public function __construct(
        public readonly float $latitude,

        public readonly float $longitude,

        public readonly ?string $countryCode,

        public readonly string $condition,

        public readonly string $description,

        public readonly string $icon,

        public readonly float $temperature,

        public readonly ?float $feelsLike,

        public readonly ?int $pressure,

        public readonly ?int $humidity,

        public readonly ?float $windSpeed,

        public readonly ?string $windDirection,

        public readonly ?int $cloudiness,

        public readonly ?int $visibility,

        /**
         * Seconds difference from UTC
         * To use with Carbon's timezone method, divide by 3600
         */
        public readonly int $timezone,

        public readonly Carbon $sunrise,

        public readonly Carbon $sunset,

        public readonly Carbon $calculatedAt
    ) {
    }
}
```


