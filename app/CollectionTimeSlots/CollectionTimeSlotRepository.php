<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

use Carbon\Carbon;
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
        Carbon $dateFrom,
        Carbon $dateTo,
        ?string $serviceCode = null,
    ): ResourcesInterface {
        $queryParams = array_filter([
            'country_code' => $countryCode,
            'postal_code'  => $postalCode,
            'date_from'    => $dateFrom->toIso8601String(),
            'date_to'      => $dateTo->toIso8601String(),
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
