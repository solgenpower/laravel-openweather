<?php

namespace SolgenPower\LaravelOpenWeather;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SolgenPower\LaravelOpenWeather\Contracts\OpenWeatherAPI;
use SolgenPower\LaravelOpenWeather\DataTransferObjects\Weather;
use SolgenPower\LaravelOpenWeather\Enums\TemperatureUnit;

class OpenWeather implements OpenWeatherAPI
{
    private string $apiKey;

    private string $currentEndpoint;

    private TemperatureUnit $temperatureUnit;

    private int $cacheDuration;

    public function __construct(
        array $config,
    ) {
        $this->apiKey = $config['api-key'];
        $this->currentEndpoint = $config['current-endpoint'];
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

    private function getCurrentWeather(array $query): Weather
    {
        $query['appid'] = $this->apiKey;
        $query['units'] = $this->temperatureUnit->value;

        $cacheKey = md5(serialize($query));

        $data = Cache::remember(
            "openweather:current:{$cacheKey}",
            $this->cacheDuration,
            fn () => Http::get($this->currentEndpoint, $query)->throw()->json()
        );

        return Weather::fromApiResponse($data);
    }
}
