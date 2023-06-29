<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getWeatherData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.open-meteo.com/v1/forecast?latitude=48.82&longitude=2.42&hourly=temperature_2m&models=gfs_global&current_weather=true'
        );

        return $response->toArray();
    }
}
