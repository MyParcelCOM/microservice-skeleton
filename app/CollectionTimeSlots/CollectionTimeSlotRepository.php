<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\JsonApi\Resources\PromiseResources;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class CollectionTimeSlotRepository
{
    protected CarrierApiGatewayInterface $carrierApiGateway;

    public function __construct(CarrierApiGatewayInterface $carrierApiGateway)
    {
        $this->carrierApiGateway = $carrierApiGateway;
    }

    public function getCollectionTimeSlots(
        string $countryCode,
        string $postalCode,
        string $dateFrom,
        string $dateTo,
        string $serviceCode,
    ): ResourcesInterface {
        $queryParams = array_filter([
            'country_code' => $countryCode,
            'postal_code'  => $postalCode,
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
            'service_code' => $serviceCode,
        ]);

        // todo: Implement a request to the carrier with the CarrierApiGateway using the queryString.
        // $response = $this->carrierApiGateway->get('url', $queryParams);

        // todo: Map the result into CollectionTimeSlot resources.

        return new PromiseResources(
            // todo: Return the CollectionTimeSlots.
        );
    }


}
