<?php

namespace SolgenPower\LaravelOpenweather;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SolgenPower\LaravelOpenweather\Contracts\OpenWeatherAPI;
use SolgenPower\LaravelOpenweather\DataTransferObjects\Weather;
use SolgenPower\LaravelOpenweather\Enums\TemperatureUnit;

class OpenWeather implements OpenWeatherAPI
{
    private string $apiKey;

    private string $currentEndpoint;

    private string $iconEndpoint;

    private array $iconMap;

    private TemperatureUnit $temperatureUnit;

    private int $cacheDuration;

    public function __construct(
        array $config,
    ) {
        $this->apiKey = $config['api-key'];
        $this->currentEndpoint = $config['current-endpoint'];
        $this->iconEndpoint = $config['icon-endpoint'];
        $this->iconMap = $config['icon-map'];
        $this->temperatureUnit = TemperatureUnit::from($config['temperature-unit']);
        $this->cacheDuration = $config['cache-duration'];
    }

    /**
     * Override specific request temperate unit type from default in config
     */
    public function asTemperatureUnit(TemperatureUnit $temperatureUnit): self
    {
        $this->temperatureUnit = $temperatureUnit;

        return $this;
    }

    /**
     * Fetch Weather information using coordinates
     */
    public function coordinates(string $latitude, string $longitude): Weather
    {
        return $this->getCurrentWeather([
            'lat' => $latitude,
            'lon' => $longitude,
        ]);
    }

    /**
     * Fetch Weather information using Zip Code and Country Code
     */
    public function zip(string $zip, string $country): Weather
    {
        return $this->getCurrentWeather([
            'zip' => implode(',', [$zip, $country]),
        ]);
    }

    /**
     * Fetch Weather information using City name, State Code, and Country Code
     */
    public function city(string $city, string $stateCode = '', string $countryCode = ''): Weather
    {
        return $this->getCurrentWeather([
            'q' => implode(',', array_filter([$city, $stateCode, $countryCode])),
        ]);
    }

    private function getCurrentWeather(array $params): Weather
    {
        $params['appid'] = $this->apiKey;
        $params['units'] = $this->temperatureUnit->value;
        $urlParams = http_build_query($params);

        $data = Cache::remember(
            "openweather:current:{$urlParams}",
            $this->cacheDuration,
            fn () => Http::get("{$this->currentEndpoint}?{$urlParams}")->throw()->json()
        );

        $weather = [
            'latitude' => $data['coord']['lat'],
            'longitude' => $data['coord']['lon'],
            'countryCode' => $data['sys']['country'] ?? null,
            'condition' => $data['weather'][0]['main'],
            'description' => $data['weather'][0]['description'],
            'icon' => $this->getIconUrl($data['weather'][0]['icon']),
            'temperature' => $data['main']['temp'],
            'feelsLike' => $data['main']['feels_like'] ?? null,
            'pressure' => $data['main']['pressure'] ?? null,
            'humidity' => $data['main']['humidity'] ?? null,
            'windSpeed' => $data['wind']['speed'] ?? null,
            'windDirection' => isset($data['wind']['deg']) ? degreesToCardinal($data['wind']['deg']) : null,
            'cloudiness' => $data['clouds']['all'] ?? null,
            'visibility' => $data['visibility'] ?? null,
            'timezone' => $data['timezone'] ?? 0,
            'sunrise' => Carbon::parse($data['sys']['sunrise']),
            'sunset' => Carbon::parse($data['sys']['sunset']),
            'calculatedAt' => Carbon::parse($data['dt']),
        ];

        return new Weather(...$weather);
    }

    /**
     * Get the full path string for a weather condition icon code
     */
    public function getIconUrl(string $icon): string
    {
        $iconFile = $this->iconMap[$icon];

        return "{$this->iconEndpoint}{$iconFile}";
    }
}
