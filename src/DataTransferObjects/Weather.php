<?php

namespace SolgenPower\LaravelOpenWeather\DataTransferObjects;

use Illuminate\Support\Carbon;
use Illuminate\Support\Traits\Macroable;

class Weather
{
    use Macroable;

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

    public static function fromApiResponse(array $data): self
    {
        $weather = [
            'latitude' => $data['coord']['lat'],
            'longitude' => $data['coord']['lon'],
            'countryCode' => $data['sys']['country'] ?? null,
            'condition' => $data['weather'][0]['main'],
            'description' => $data['weather'][0]['description'],
            'icon' => iconCodeToUrl($data['weather'][0]['icon']),
            'temperature' => $data['main']['temp'],
            'feelsLike' => $data['main']['feels_like'] ?? null,
            'pressure' => $data['main']['pressure'] ?? null,
            'humidity' => $data['main']['humidity'] ?? null,
            'windSpeed' => $data['wind']['speed'] ?? null,
            'windAngle' => $data['wind']['deg'] ?? null,
            'windDirection' => isset($data['wind']['deg']) ? degreesToCardinal($data['wind']['deg']) : null,
            'cloudiness' => $data['clouds']['all'] ?? null,
            'visibility' => $data['visibility'] ?? null,
            'timezone' => $data['timezone'] ?? 0,
            'sunrise' => Carbon::parse($data['sys']['sunrise']),
            'sunset' => Carbon::parse($data['sys']['sunset']),
            'calculatedAt' => Carbon::parse($data['dt']),
        ];

        return new self(...$weather);
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'countryCode' => $this->countryCode,
            'condition' => $this->condition,
            'description' => $this->description,
            'icon' => $this->icon,
            'temperature' => $this->temperature,
            'feelsLike' => $this->feelsLike,
            'pressure' => $this->pressure,
            'humidity' => $this->humidity,
            'windSpeed' => $this->windSpeed,
            'windAngle' => $this->windAngle,
            'windDirection' => $this->windDirection,
            'cloudiness' => $this->cloudiness,
            'visibility' => $this->visibility,
            'timezone' => $this->timezone,
            'sunrise' => $this->sunrise,
            'sunset' => $this->sunset,
            'calculatedAt' => $this->calculatedAt,
        ];
    }
}
