<?php

class WeatherFunctionalTest extends \Silex\WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../app/index.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }

    public function testResponseIsOkay()
    {
        $client = $this->createClient();

        $client->request('GET', '/weather?lat=43.659943999999996&lon=-79.75135949999999');

        $this->assertTrue($client->getResponse()->isOk());
    }

    public function testResponseIsNotOkay()
    {
        $client = $this->createClient();

        $client->request('GET', '/weather');

        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }

}
