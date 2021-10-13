<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GoogleGeocoder implements GeocoderInterface
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
//    return null;
   $data = $this->service->fetchCoordinates($this->formatAddress($address), 'https://maps.googleapis.com/maps/api/geocode/json');
    if (count($data['results']) !== 0) {
      return new Coordinates(
          $data['results'][0]['geometry']['location']['lat'],
          $data['results'][0]['geometry']['location']['lng'],
          $data['results'][0]['geometry']['location_type']
      );
    }

    return null;
  }

  private function formatAddress(Address $address): array
  {
    $apiKey = $_ENV["GOOGLE_GEOCODING_API_KEY"];

    return [
        'address' => $address->getStreet(),
        'components' => implode('|', [
            "country:{$address->getCountry()}",
            "locality:{$address->getCity()}",
            "postal_code:{$address->getPostcode()}"
        ]),
        'key' => $apiKey
    ];
  }

}
