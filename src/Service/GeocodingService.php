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

    /**
     * Retrieves the geographical coordinates (longitude and latitude)
     * for a given address using the geocode.maps.co API.
     *
     * @param string $address The address to geocode.
     * 
     * @return array An associative array containing 'longitude' and 'latitude'.
     *
     * @throws \Exception If no coordinates are found for the given address.
     */

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
