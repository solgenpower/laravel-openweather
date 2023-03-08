<?php

namespace Solgenpower\LaravelOpenweather;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Solgenpower\LaravelOpenweather\Contracts\OpenWeatherAPI;
use Solgenpower\LaravelOpenweather\DataTransferObjects\Weather;

class OpenWeather implements OpenWeatherAPI
{
    private string $apiKey;

    private string $apiCurrentEndpoint;

    private string $iconEndpoint;

    private string $units;

    private int $cacheDuration;

    public function __construct(
        array $config,
    ) {
        $this->apiKey = $config['api-key'];
        $this->apiCurrentEndpoint = $config['api-current-endpoint'];
        $this->iconEndpoint = $config['icon-endpoint'];
        $this->units = $config['units'];
        $this->cacheDuration = $config['cache-duration'];
    }

    /**
     * Fetch Weather information using Corrdinates
     */
    public function coordinates(string $latitude, string $longitude): Weather
    {
        return Cache::remember(
            "ow-coordinates-{$latitude}-{$longitude}",
            $this->cacheDuration,
            fn () => $this->getWeather("{$this->apiCurrentEndpoint}weather?lat={$latitude}&lon={$longitude}")
        );
    }

    /**
     * Fetch Weather information using Zip Code and Country Code
     */
    public function zip(string $zip, string $country): Weather
    {
        return Cache::remember(
            "ow-zip-{$zip}-{$country}",
            $this->cacheDuration,
            fn () => $this->getWeather("{$this->apiCurrentEndpoint}weather?zip={$zip},{$country}")
        );
    }

    /**
     * Fetch Weather information using City name, State Code, and Country Code
     */
    public function city(string $city, string $stateCode = '', string $countryCode = ''): Weather
    {
        return Cache::remember(
            "ow-city-{$city}-{$stateCode}-{$countryCode}",
            $this->cacheDuration,
            fn () => $this->getWeather("{$this->apiCurrentEndpoint}weather?q={$city},{$stateCode},{$countryCode}")
        );
    }

    private function getWeather($url): Weather
    {
        $response = Http::get("{$url}&appid={$this->apiKey}&units={$this->units}")->json();

        $weather = [
            'latitude' => $response['coord']['lat'],
            'longitude' => $response['coord']['lon'],
            'countryCode' => $response['sys']['country'] ?? null,
            'condition' => $response['weather'][0]['main'],
            'description' => $response['weather'][0]['description'],
            'icon' => $this->iconEndpoint.$response['weather'][0]['icon'],
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
}
