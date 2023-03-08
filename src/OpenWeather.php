<?php

namespace SolgenPower\LaravelOpenweather;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SolgenPower\LaravelOpenweather\Contracts\OpenWeatherAPI;
use SolgenPower\LaravelOpenweather\DataTransferObjects\Weather;
use Enum\TemperatureUnitType;

class OpenWeather implements OpenWeatherAPI
{
    private string $apiKey;

    private string $currentEndpoint;

    private string $iconEndpoint;

    private array $iconMap;

    private TemperatureUnitType $tempUnit;

    private int $cacheDuration;

    public function __construct(
        array $config,
    ) {
        $this->apiKey = $config['api-key'];
        $this->currentEndpoint = $config['current-endpoint'];
        $this->iconEndpoint = $config['icon-endpoint'];
        $this->iconMap = $config['icon-map'];
        $this->tempUnit = TemperatureUnitType::tryFrom($config['temp-unit']);
        $this->cacheDuration = $config['cache-duration'];
    }

    /**
     * Override specific request temperate unit type from default in config
     *
     * @param TemperatureUnitType $temperatureUnitType
     * @return self
     */
    public function asTemperatureUnit(TemperatureUnitType $temperatureUnitType): self {
        $this->tempUnit = $temperatureUnitType;

        return $this;
    }

    /**
     * Fetch Weather information using coordinates
     *
     * @param string $latitude
     * @param string $longitude
     * @return Weather
     */
    public function coordinates(string $latitude, string $longitude): Weather
    {
        return Cache::remember(
            "openweather:coordinates:{$latitude}-{$longitude}",
            $this->cacheDuration,
            fn () => $this->getWeather([
                "lat" => $latitude,
                "lon" => $longitude
            ])
        );
    }

    /**
     * Fetch Weather information using Zip Code and Country Code
     *
     * @param string $zip
     * @param string $country
     * @return Weather
     */
    public function zip(string $zip, string $country): Weather
    {
        return Cache::remember(
            "openweather:zip:{$zip}-{$country}",
            $this->cacheDuration,
            fn () => $this->getWeather([
                "zip" => implode(",", [$zip, $country])
            ])
        );
    }

    /**
     * Fetch Weather information using City name, State Code, and Country Code
     *
     * @param string $city
     * @param string $stateCode
     * @param string $countryCode
     * @return Weather
     */
    public function city(string $city, string $stateCode = '', string $countryCode = ''): Weather
    {
        return Cache::remember(
            "openweather:city:{$city}-{$stateCode}-{$countryCode}",
            $this->cacheDuration,
            fn () => $this->getWeather([
                "q" => implode(",", array_filter([$city, $stateCode, $countryCode]))
            ])
        );
    }

    private function getWeather(array $params): Weather
    {
        $params["appid"] = $this->apiKey;
        $params["units"] = $this->tempUnit->name;
        $urlParams = http_build_query($params);

        $response = Http::get("{$this->currentEndpoint}weather?{$urlParams}")->json();

        $weather = [
            'latitude' => $response['coord']['lat'],
            'longitude' => $response['coord']['lon'],
            'countryCode' => $response['sys']['country'] ?? null,
            'condition' => $response['weather'][0]['main'],
            'description' => $response['weather'][0]['description'],
            'icon' => $this->getIconUrl($response['weather'][0]['icon']),
            'temperature' => $response['main']['temp'],
            'feelsLike' => $response['main']['feels_like'] ?? null,
            'pressure' => $response['main']['pressure'] ?? null,
            'humidity' => $response['main']['humidity'] ?? null,
            'windSpeed' => $response['wind']['speed'] ?? null,
            'windDirection' => isset($response['wind']['deg']) ? degreesToCardinal($response['wind']['deg']) : null,
            'cloudiness' => $response['clouds']['all'] ?? null,
            'visibility' => $response['visibility'] ?? null,
            'timezone' => $response['timezone'] ?? 0,
            'sunrise' => Carbon::parse($response['sys']['sunrise']),
            'sunset' => Carbon::parse($response['sys']['sunset']),
            'calculatedAt' => Carbon::parse($response['dt']),
        ];

        return new Weather(...$weather);
    }

    public function getIconUrl(string $icon): string
    {
        $iconFile = $this->iconMap[$icon];

        return "{$this->iconEndpoint}{$iconFile}";
    }
}
