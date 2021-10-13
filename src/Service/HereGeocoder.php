<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class HereGeocoder implements GeocoderInterface
{
  /**
   * @var GeocoderService
   */
  private GeocoderService $service;

  public function __construct(GeocoderService $service) {
    $this->service = $service;
  }
  public function geocode(Address $address): ?Coordinates
  {
    $data = $this->service->fetchCoordinates($this->formatAddress($address), 'https://geocode.search.hereapi.com/v1/geocode');
    if (count($data['items']) !== 0) {
      return new Coordinates(
          $data['items'][0]['position']['lat'],
          $data['items'][0]['position']['lng'],
          $data['items'][0]['resultType']
      );
    }

    return null;
  }

  private function formatAddress(Address $address): array {
    $apiKey = $_ENV["HEREMAPS_GEOCODING_API_KEY"];

    return [
        'qq' => implode(';', [
            "country={$address->getCountry()}",
            "city={$address->getCity()}",
            "street={$address->getStreet()}",
            "postalCode={$address->getPostcode()}"
        ]),
        'apiKey' => $apiKey
    ];
  }
}
