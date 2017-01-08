<?php

namespace Weatherbot;

class Weather
{
    /** @var const url */
    const URL = 'http://api.openweathermap.org/data/2.5/weather';

    /** @var \GuzzleHttp\Client $client*/
    private $client;

    /*
     * Weather constructor
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Makes a call to the openweathermap's /weather endpoint
     *
     * @param array $params
     *
     * @return \GuzzleHttp\Psr7\Response $result
     */
    public function getWeather(Array $params)
    {
        $result = $this->client->request('GET', self::URL, [
            'query' => $params
        ]);

        return $result;
    }
}
