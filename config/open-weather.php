<?php

return [

    /**
     * API Key for Open Weather
     */
    'api-key' => env('OPENWEATHER_API_KEY', ''),

    /**
     * Endpoint for the Current Weather
     */
    'current-endpoint' => env('OPENWEATHER_CURRENT_ENDPOINT', 'https://api.openweathermap.org/data/2.5/weather/'),

    /**
     * Endpoint for the Weather Condition icons
     * Reference: https://openweathermap.org/weather-conditions
     */
    'icon-endpoint' => env('OPENWEATHER_ICON_ENDPOINT', 'https://openweathermap.org/img/wn/'),

    /**
     * Map icon code to actual icon filenames
     */
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
         * Night Icons
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
     * Cache duration default in seconds, 60 * 10 is 10 minutes
     */
    'cache-duration' => 60 * 10,

    /**
     * standard => Kelvin
     * imperial => Fahrenheit
     * metric => Celsius
     */
    'temperature-unit' => env('OPENWEATHER_TEMPERATURE_UNIT', 'imperial'),

];
