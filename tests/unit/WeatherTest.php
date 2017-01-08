<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class WeatherTest extends TestCase
{
    protected $url;

    public function setUp()
    {
        $this->url = 'http://api.openweathermap.org/data/2.5/weather';
    }

    public function testGetWeatherShouldReceiveOkay()
    {
        $validRequest = '{
            "coord": {
                "lon": -79.77,
                "lat": 43.68
            },
            "weather": [
                {
                    "id": 620,
                    "main": "Snow",
                    "description": "light shower snow",
                    "icon": "13n"
                }
            ],
            "base": "stations",
            "main": {
                "temp": 271.47,
                "pressure": 1015,
                "humidity": 86,
                "temp_min": 271.15,
                "temp_max": 272.15
            },
            "visibility": 9656,
            "wind": {
                "speed": 6.2,
                "deg": 270
            },
            "clouds": {
                "all": 90
            },
            "dt": 1482889740,
            "sys": {
                "type": 1,
                "id": 3721,
                "message": 0.1946,
                "country": "CA",
                "sunrise": 1482929534,
                "sunset": 1482961792
            },
            "id": 5907364,
            "name": "Brampton",
            "cod": 200
        }';

        $invalidRequest = '{"cod":"502","message":"Error: Not found city"}';

        $mock = new MockHandler([
            new Response(200, (array) $validRequest),
            new Response(502, (array) $invalidRequest)
        ]);

        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler' => $handler]);
        $weather = new \Weatherbot\Weather($client);

        $parameters = [
            'lat'   => '22',
            'lon'   => '23',
            'appid' => 'appid'
        ];

        $result = $weather->getWeather($parameters);

        $invalidParameters = [
            'lat'   => '22',
            'lon'   => '',
            'appid' => 'appid'
        ];

        $this->assertEquals(200, $result->getStatusCode());

        try {
            $weather->getWeather($invalidParameters);
        } catch (Exception $exception) {
            $this->expectExceptionMessage($exception->getMessage());
        }
    }
}
