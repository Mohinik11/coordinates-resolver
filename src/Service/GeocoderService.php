<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;

class GeocoderService
{
  /**
   * @var Client
   */
  private Client $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function fetchCoordinates($params, $url): array
  {
    $params = [
        'query' => $params
    ];

    $response = $this->client->get($url, $params);

    return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
  }
}