<?php

/**
 * Function to convert 0-360 degrees into cardinal direction abbreviations
 */
if (! function_exists('degreesToCardinal')) {
    function degreesToCardinal(int $degrees)
    {
        $cardinalDirections = [
            'N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE',
            'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW',
        ];

        $numOfDirections = count($cardinalDirections);

        $degreesPerDirection = 360 / $numOfDirections;

        $index = round(($degrees % 360) / $degreesPerDirection);

        return $cardinalDirections[($index % $numOfDirections)];
    }
}

if (! function_exists('iconCodeToUrl')) {
    function iconCodeToUrl(string $code)
    {
        $iconMap = config('open-weather.icon-map');

        $iconEndpoint = config('open-weather.icon-endpoint');

        $iconFile = $iconMap[$code];

        return "{$iconEndpoint}{$iconFile}";
    }
}
