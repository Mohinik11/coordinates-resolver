<?php

declare(strict_types=1);

namespace App\ValueObject;

class Coordinates
{
    private float $lat;
    private float $lng;
    private string $locationType;

    public function __construct(float $lat, float $lng, string $locationType)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->locationType = $locationType;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function getLocationType(): string
    {
      return $this->locationType;
    }
}
