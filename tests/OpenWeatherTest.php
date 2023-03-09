<?php

namespace SolgenPower\LaravelOpenweather\Test;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use SolgenPower\LaravelOpenweather\DataTransferObjects\Weather;
use SolgenPower\LaravelOpenweather\Enums\TemperatureUnit;
use SolgenPower\LaravelOpenweather\Facades\OpenWeather;
use SolgenPower\LaravelOpenweather\OpenWeatherServiceProvider;

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
        $this->assertTrue($weather->icon === OpenWeather::getIconUrl('10d'));
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
        $this->assertTrue($weather->icon === OpenWeather::getIconUrl('01d'));
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
        $this->assertTrue($weather->icon === OpenWeather::getIconUrl('09d'));
        $this->assertTrue($weather->temperature === 280.32);
        $this->assertTrue($weather->feelsLike === null);
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

    /** @test */
    public function it_caches_the_weather_infromation()
    {
        $apiEndpoint = config('open-weather.api-current-endpoint');
        $cacheDuration = config('open-weather.cache-duration');

        $fixture1 = json_decode(file_get_contents(__DIR__.'/fixtures/test-coordinates.json'), true);
        $fixture2 = json_decode(file_get_contents(__DIR__.'/fixtures/test-zip.json'), true);
        $fixture3 = json_decode(file_get_contents(__DIR__.'/fixtures/test-city.json'), true);

        Http::fake([
            "{$apiEndpoint}*" => Http::sequence()
                ->push($fixture1) //for $coordinatesWeather
                ->push($fixture2) //for $coordinatesWeatherCached
                ->push($fixture1) //for $zipWeather
                ->push($fixture2) //for $zipWeatherCached
                ->push($fixture1) //for $cityWeather
                ->push($fixture2) //for $cityWeatherCached
                ->push($fixture3) //for $coordinatesWeatherExpired
                ->push($fixture3) //for $zipWeatherExpired
                ->push($fixture3), //for $cityWeatherExpired
        ]);

        $coordinatesWeather = OpenWeather::coordinates('38.897957', '-77.036560');
        $coordinatesWeatherCached = OpenWeather::coordinates('38.897957', '-77.036560');

        $zipWeather = OpenWeather::zip('90210', 'US');
        $zipWeatherCached = OpenWeather::zip('90210', 'US');

        $cityWeather = OpenWeather::city('Tucson', 'AZ', 'US');
        $cityWeatherCached = OpenWeather::city('Tucson', 'AZ', 'US');

        $this->travel($cacheDuration + 3)->seconds();

        $coordinatesWeatherExpired = OpenWeather::coordinates('38.897957', '-77.036560');
        $zipWeatherExpired = OpenWeather::zip('90210', 'US');
        $cityWeatherExpired = OpenWeather::city('Tucson', 'AZ', 'US');

        $this->assertTrue($coordinatesWeather->latitude === $coordinatesWeatherCached->latitude);
        $this->assertTrue($coordinatesWeather->longitude === $coordinatesWeatherCached->longitude);

        $this->assertTrue($zipWeather->latitude === $zipWeatherCached->latitude);
        $this->assertTrue($zipWeather->longitude === $zipWeatherCached->longitude);

        $this->assertTrue($cityWeather->latitude === $cityWeatherCached->latitude);
        $this->assertTrue($cityWeather->longitude === $cityWeatherCached->longitude);

        $this->assertNotTrue($coordinatesWeatherCached->longitude === $coordinatesWeatherExpired->longitude);
        $this->assertNotTrue($coordinatesWeatherCached->latitude === $coordinatesWeatherExpired->latitude);

        $this->assertNotTrue($zipWeatherCached->longitude === $zipWeatherExpired->longitude);
        $this->assertNotTrue($zipWeatherCached->latitude === $zipWeatherExpired->latitude);

        $this->assertNotTrue($cityWeatherCached->longitude === $cityWeatherExpired->longitude);
        $this->assertNotTrue($cityWeatherCached->latitude === $cityWeatherExpired->latitude);
    }

    /** @test */
    public function it_gets_the_full_path_for_the_weather_condition_icon()
    {
        $iconPath = OpenWeather::getIconUrl('09d');

        $this->assertEquals($iconPath, 'https://openweathermap.org/img/wn/09d.png');
    }

    /** @test */
    public function it_fetches_values_in_different_temperature_units()
    {
        $apiEndpoint = config('open-weather.api-current-endpoint');

        $metricFixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-metric.json'), true);
        $imperialFixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-imperial.json'), true);

        Http::fake([
            "{$apiEndpoint}*units=metric" => Http::response($metricFixture),
            "{$apiEndpoint}*units=imperial" => Http::response($imperialFixture),
        ]);

        $metricWeather = OpenWeather::asTemperatureUnit(TemperatureUnit::Metric)->zip('90210', 'US');
        $imperialWeather = OpenWeather::asTemperatureUnit(TemperatureUnit::Imperial)->zip('90210', 'US');

        $this->assertNotEquals($metricWeather->windSpeed, $imperialWeather->windSpeed);
        $this->assertEquals($metricWeather->windDirection, $imperialWeather->windDirection);
        $this->assertEquals($metricWeather->temperature, 8.48);
        $this->assertEquals($imperialWeather->temperature, 47.26);
    }
}
