<?php

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
