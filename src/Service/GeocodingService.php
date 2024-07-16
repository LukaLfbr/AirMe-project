<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodingService
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function getCoordinates(string $address): array
    {
        $response = $this->httpClient->request(
            'GET',
            'https://geocode.maps.co/search',
            [
                'query' => [
                    'q' => $address,
                    'api_key' => $this->apiKey
                ]
            ]
        );

        $data = $response->toArray();

        if (!empty($data)) {
            return [
                'longitude' => $data[0]['lon'],
                'latitude' => $data[0]['lat']
            ];
        }

        throw new \Exception('No coordinates found for address: ' . $address);
    }
}
