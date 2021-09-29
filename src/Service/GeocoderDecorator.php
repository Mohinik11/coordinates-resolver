<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;

class GeocoderDecorator
{

  public array $geocoders;

  public function __construct(array $geocoders) {
    $this->geocoders[] = $geocoders;
  }

  public function getCoordinates(Address $address) {
    foreach ($this->geocoders as $geocoder) {
      $coordinates = $geocoder->geocode($address);
      if (!empty($coordinates)) {
        break;
      }
      return $coordinates;
    }
  }

}