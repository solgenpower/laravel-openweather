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
