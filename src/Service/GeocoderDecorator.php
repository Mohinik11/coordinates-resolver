<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Psr\Log\LoggerInterface;

class GeocoderDecorator
{

  /**
   * @var array
   */
  public array $geocoders;

  const ALLOWED_TYPE = ['houseNumber', 'ROOFTOP'];

  /**
   * @var LoggerInterface
   */
  private LoggerInterface $logger;

  public function __construct(array $geocoders, LoggerInterface $logger)
  {
    $this->geocoders = $geocoders;
    $this->logger = $logger;
  }

  public function getCoordinates(Address $address): ?Coordinates
  {
    $coordinates = null;
    try {
      foreach ($this->geocoders as $geocoder) {
        $coordinates = $geocoder->geocode($address);
        if (!empty($coordinates)) {
          break;
        }
      }
    } catch (\Exception $e) {
      $this->logger->error('Error while fetching coordinates');
    }

    if(in_array($coordinates->getLocationType(), self::ALLOWED_TYPE))
    {
      return $coordinates;
    }

    return null;
  }

}