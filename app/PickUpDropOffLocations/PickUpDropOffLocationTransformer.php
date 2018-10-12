<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\JsonApi\Transformers\TransformerException;

class PickUpDropOffLocationTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'pickup-dropoff-locations';

    /**
     * @param PickUpDropOffLocation $pickUpDropOffLocation
     * @return string
     */
    public function getId($pickUpDropOffLocation): string
    {
        $this->validateModel($pickUpDropOffLocation);

        return $pickUpDropOffLocation->getId();
    }

    /**
     * @param PickUpDropOffLocation $pickUpDropOffLocation
     * @return array
     */
    public function getAttributes($pickUpDropOffLocation): array
    {
        $this->validateModel($pickUpDropOffLocation);

        $address = $pickUpDropOffLocation->getAddress();
        $position = $pickUpDropOffLocation->getPosition();

        $openingHours = [];
        foreach ($pickUpDropOffLocation->getOpeningHours() as $openingHour) {
            $openingHours[] = [
                'day'    => $openingHour->getDay(),
                'open'   => $openingHour->getOpen() ? $openingHour->getOpen()->format('H:i') : '00:00',
                'closed' => $openingHour->getClosed() ? $openingHour->getClosed()->format('H:i') : '00:00',
            ];
        }

        return array_filter([
            'categories'    => $pickUpDropOffLocation->getCategories(),
            'address'       => array_filter([
                'street_1'             => $address->getStreet1(),
                'street_2'             => $address->getStreet2(),
                'street_number'        => $address->getStreetNumber(),
                'street_number_suffix' => $address->getStreetNumberSuffix(),
                'postal_code'          => $address->getPostalCode(),
                'city'                 => $address->getCity(),
                'region_code'          => $address->getRegionCode(),
                'country_code'         => $address->getCountryCode(),
                'first_name'           => $address->getFirstName(),
                'last_name'            => $address->getLastName(),
                'company'              => $address->getCompany(),
                'email'                => $address->getEmail(),
                'phone_number'         => $address->getPhoneNumber(),
            ]),
            'opening_hours' => $openingHours,
            'position'      => array_filter([
                'latitude'  => $position->getLatitude(),
                'longitude' => $position->getLongitude(),
            ]),
        ]);
    }

    /**
     * @param PickUpDropOffLocation $pickUpDropOffLocation
     * @return array
     */
    public function getMeta($pickUpDropOffLocation): array
    {
        $this->validateModel($pickUpDropOffLocation);

        return [
            'distance' => $pickUpDropOffLocation->getDistance(),
        ];
    }

    /**
     * @param PickUpDropOffLocation $pickUpDropOffLocation
     * @throws TransformerException
     */
    protected function validateModel($pickUpDropOffLocation): void
    {
        if (!$pickUpDropOffLocation instanceof PickUpDropOffLocation) {
            throw new TransformerException(
                'Invalid model supplied, expected instance of `PickUpDropOffLocation`, got=> ' . get_class($pickUpDropOffLocation)
            );
        }
    }
}
