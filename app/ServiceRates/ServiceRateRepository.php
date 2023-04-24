<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class ServiceRateRepository
{
    public function __construct(
        private readonly CarrierApiGatewayInterface $carrierApiGateway,
    ) {
    }

    public function getServiceRates(array $data): ResourcesInterface
    {
        // TODO: Get service rates for given shipment from carrier (use CarrierApiGateway).
        // TODO: Map data to ServiceRate objects.
        // TODO: Put ServiceRate objects in an object that implements ResourcesInterface
    }
}
