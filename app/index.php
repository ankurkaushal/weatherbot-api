<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request as Request;
use GuzzleHttp\Client;

$application = new Application();

$application['access_key'] = '3c27c41d7a2fe74c8a9a4ef95934e6d6';

$application['weather'] = function () use ($application) {
   $client = new Client();

   return new Weatherbot\Weather($client);
};


$application->get('/weather', function(Request $request) use ($application) {
    $lat  = $request->query->get('lat');
    $lon  = $request->query->get('lon');

    $parameters = [
        'lat'   => $lat,
        'lon'   => $lon,
        'units' => 'metric',
        'appid' => $application['access_key']
    ];

    try {
        $data = $application['weather']->getWeather($parameters);
    } catch (Exception $exception) {
        $response             = $exception->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();

        return $application->json(json_decode($responseBodyAsString), $response->getStatusCode());
    }

    return $application->json(json_decode($data->getBody()), $data->getStatusCode());
});

return $application;
