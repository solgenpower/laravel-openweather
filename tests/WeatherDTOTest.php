<?php

namespace SolgenPower\LaravelOpenWeather\Test;

use Illuminate\Support\Carbon;
use SolgenPower\LaravelOpenWeather\DataTransferObjects\Weather;

class WeatherDTOTest extends BaseTestCase
{
    /** @test */
    public function it_creates_a_dto_from_an_api_response()
    {
        $responseFixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-coordinates.json'), true);

        $weather = Weather::fromApiResponse($responseFixture);

        $this->assertInstanceOf(Weather::class, $weather);
        $this->assertTrue($weather->latitude === 44.34);
        $this->assertTrue($weather->longitude === 10.99);
        $this->assertTrue($weather->countryCode === 'IT');
        $this->assertTrue($weather->city === 'Zocca');
        $this->assertTrue($weather->condition === 'Rain');
        $this->assertTrue($weather->description === 'moderate rain');
        $this->assertTrue($weather->icon === iconCodeToUrl('10d'));
        $this->assertTrue($weather->temperature === 298.48);
        $this->assertTrue($weather->feelsLike === 298.74);
        $this->assertTrue($weather->pressure === 1015);
        $this->assertTrue($weather->humidity === 64);
        $this->assertTrue($weather->windSpeed === 0.62);
        $this->assertTrue($weather->windAngle === 349);
        $this->assertTrue($weather->windDirection === degreesToCardinal(349));
        $this->assertTrue($weather->cloudiness === 100);
        $this->assertTrue($weather->visibility === 10000);
        $this->assertTrue($weather->timezone === 7200);
        $this->assertTrue($weather->sunrise->valueOf() == (1661834187 * 1000));
        $this->assertTrue($weather->sunset->valueOf() == (1661882248 * 1000));
        $this->assertTrue($weather->calculatedAt->valueOf() == (1661870592 * 1000));
    }

    /** @test */
    public function it_creates_an_array_representation_of_the_dto()
    {
        $responseFixture = json_decode(file_get_contents(__DIR__.'/fixtures/test-coordinates.json'), true);

        $weather = Weather::fromApiResponse($responseFixture);

        $weatherArray = $weather->toArray();

        $this->assertIsArray($weatherArray);
        $this->assertTrue($weatherArray['latitude'] === 44.34);
        $this->assertTrue($weatherArray['longitude'] === 10.99);
        $this->assertTrue($weatherArray['countryCode'] === 'IT');
        $this->assertTrue($weatherArray['city'] === 'Zocca');
        $this->assertTrue($weatherArray['condition'] === 'Rain');
        $this->assertTrue($weatherArray['description'] === 'moderate rain');
        $this->assertTrue($weatherArray['icon'] === iconCodeToUrl('10d'));
        $this->assertTrue($weatherArray['temperature'] === 298.48);
        $this->assertTrue($weatherArray['feelsLike'] === 298.74);
        $this->assertTrue($weatherArray['pressure'] === 1015);
        $this->assertTrue($weatherArray['humidity'] === 64);
        $this->assertTrue($weatherArray['windSpeed'] === 0.62);
        $this->assertTrue($weatherArray['windAngle'] === 349);
        $this->assertTrue($weatherArray['windDirection'] === degreesToCardinal(349));
        $this->assertTrue($weatherArray['cloudiness'] === 100);
        $this->assertTrue($weatherArray['visibility'] === 10000);
        $this->assertTrue($weatherArray['timezone'] === 7200);
        $this->assertTrue($weatherArray['sunrise']->valueOf() == (1661834187 * 1000));
        $this->assertTrue($weatherArray['sunset']->valueOf() == (1661882248 * 1000));
        $this->assertTrue($weatherArray['calculatedAt']->valueOf() == (1661870592 * 1000));

        $this->assertInstanceOf(Carbon::class, $weatherArray['sunrise']);
        $this->assertInstanceOf(Carbon::class, $weatherArray['sunset']);
        $this->assertInstanceOf(Carbon::class, $weatherArray['calculatedAt']);
    }
}
