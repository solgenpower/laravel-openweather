<?php

namespace Solgenpower\LaravelOpenweather\Test;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use Solgenpower\LaravelOpenweather\DataTransferObjects\Weather;
use Solgenpower\LaravelOpenweather\Facades\OpenWeather;
use Solgenpower\LaravelOpenweather\OpenWeatherServiceProvider;

class OpenWeatherTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            OpenWeatherServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'OpenWeather' => OpenWeather::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    /** @test */
    public function it_gets_weather_information_of_a_coordinate()
    {
        $apiEndpoint = config('open-weather.api-current-endpoint');
        $iconEndpoint = config('open-weather.icon-endpoint');

        $fixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-coordinates.json'), true);

        Http::fake([
            "{$apiEndpoint}*" => Http::response($fixture),
        ]);

        $weather = OpenWeather::coordinates('38.897957', '-77.036560');

        $this->assertInstanceOf(Weather::class, $weather);
        $this->assertTrue($weather->latitude === 44.34);
        $this->assertTrue($weather->longitude === 10.99);
        $this->assertTrue($weather->countryCode === 'IT');
        $this->assertTrue($weather->condition === 'Rain');
        $this->assertTrue($weather->description === 'moderate rain');
        $this->assertTrue($weather->icon === "{$iconEndpoint}10d");
        $this->assertTrue($weather->temperature === 298.48);
        $this->assertTrue($weather->feelsLike === 298.74);
        $this->assertTrue($weather->pressure === 1015);
        $this->assertTrue($weather->humidity === 64);
        $this->assertTrue($weather->windSpeed === 0.62);
        $this->assertTrue($weather->windDirection === degreesToCardinal(349));
        $this->assertTrue($weather->cloudiness === 100);
        $this->assertTrue($weather->visibility === 10000);
        $this->assertTrue($weather->timezone === 7200);
        $this->assertTrue($weather->sunrise->valueOf() == (1661834187 * 1000));
        $this->assertTrue($weather->sunset->valueOf() == (1661882248 * 1000));
        $this->assertTrue($weather->calculatedAt->valueOf() == (1661870592 * 1000));
    }

    /** @test */
    public function it_gets_weather_information_of_a_zip()
    {
        $apiEndpoint = config('open-weather.api-current-endpoint');
        $iconEndpoint = config('open-weather.icon-endpoint');

        $fixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-zip.json'), true);

        Http::fake([
            "{$apiEndpoint}*" => Http::response($fixture),
        ]);

        $weather = OpenWeather::zip('90210', 'US');

        $this->assertInstanceOf(Weather::class, $weather);
        $this->assertTrue($weather->latitude === 37.39);
        $this->assertTrue($weather->longitude === -122.08);
        $this->assertTrue($weather->countryCode === 'US');
        $this->assertTrue($weather->condition === 'Clear');
        $this->assertTrue($weather->description === 'clear sky');
        $this->assertTrue($weather->icon === "{$iconEndpoint}01d");
        $this->assertTrue($weather->temperature === 282.55);
        $this->assertTrue($weather->feelsLike === 281.86);
        $this->assertTrue($weather->pressure === 1023);
        $this->assertTrue($weather->humidity === 100);
        $this->assertTrue($weather->windSpeed === 1.5);
        $this->assertTrue($weather->windDirection === degreesToCardinal(350));
        $this->assertTrue($weather->cloudiness === 1);
        $this->assertTrue($weather->visibility === 10000);
        $this->assertTrue($weather->timezone === -25200);
        $this->assertTrue($weather->sunrise->valueOf() == (1560343627 * 1000));
        $this->assertTrue($weather->sunset->valueOf() == (1560396563 * 1000));
        $this->assertTrue($weather->calculatedAt->valueOf() == (1560350645 * 1000));
    }

    /** @test */
    public function it_gets_weather_information_of_a_city()
    {
        $apiEndpoint = config('open-weather.api-current-endpoint');
        $iconEndpoint = config('open-weather.icon-endpoint');

        $fixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-city.json'), true);

        Http::fake([
            "{$apiEndpoint}*" => Http::response($fixture),
        ]);

        $weather = OpenWeather::city('Tucson', 'AZ', 'US');

        $this->assertInstanceOf(Weather::class, $weather);
        $this->assertTrue($weather->latitude === 51.51);
        $this->assertTrue($weather->longitude === -0.13);
        $this->assertTrue($weather->countryCode === 'GB');
        $this->assertTrue($weather->condition === 'Drizzle');
        $this->assertTrue($weather->description === 'light intensity drizzle');
        $this->assertTrue($weather->icon === "{$iconEndpoint}09d");
        $this->assertTrue($weather->temperature === 280.32);
        $this->assertTrue($weather->feelsLike === 280.32);
        $this->assertTrue($weather->pressure === 1012);
        $this->assertTrue($weather->humidity === 81);
        $this->assertTrue($weather->windSpeed === 4.1);
        $this->assertTrue($weather->windDirection === degreesToCardinal(80));
        $this->assertTrue($weather->cloudiness === 90);
        $this->assertTrue($weather->visibility === 10000);
        $this->assertTrue($weather->timezone === 0);
        $this->assertTrue($weather->sunrise->valueOf() == (1485762037 * 1000));
        $this->assertTrue($weather->sunset->valueOf() == (1485794875 * 1000));
        $this->assertTrue($weather->calculatedAt->valueOf() == (1485789600 * 1000));
    }
}
