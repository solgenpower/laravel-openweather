<?php

namespace SolgenPower\LaravelOpenweather\DataTransferObjects;

use Illuminate\Support\Carbon;

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

        public readonly ?int $windAngle,

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
