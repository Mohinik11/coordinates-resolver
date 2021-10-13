<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ResolvedAddressRepository;
use App\Service\GeocoderDecorator;
use App\ValueObject\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
    private GeocoderDecorator $geocoder;

    public function __construct(GeocoderDecorator $geocoder)
    {
        $this->geocoder = $geocoder;
    }

  /**
   * @Route(path="/coordinates", name="geocode")
   * @param Request                   $request
   * @param ResolvedAddressRepository $repository
   *
   * @return Response
   */
    public function geocodeAction(Request $request, ResolvedAddressRepository $repository): Response
    {
        $country = $request->get('countryCode', 'lt');
        $city = $request->get('city', 'vilnius');
        $street = $request->get('street', 'jasinskio 16');
        $postcode = $request->get('postcode', '01112');
        $address = new Address($country, $city, $street, $postcode);

        $row = $repository->getByAddress($address);
//        $row = null;
        $coordinates = null;

        if(empty($row)) {
          $coordinates = $this->geocoder->getCoordinates($address);
          $repository->saveResolvedAddress($address, $coordinates);

          if (null === $coordinates) {
            return new JsonResponse([]);
          }
        } else {
          $coordinates = $row;
        }
        return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }

}