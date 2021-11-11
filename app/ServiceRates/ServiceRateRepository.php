<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Shipments\Shipment;

class ServiceRateRepository
{
    protected CarrierApiGatewayInterface $carrierApiGateway;

    public function __construct(
        CarrierApiGatewayInterface $carrierApiGateway,
    ) {
        $this->carrierApiGateway = $carrierApiGateway;
    }

    /**
     * @param Shipment $shipment
     * @return ResourcesInterface
     */
    public function getServiceRates(Shipment $shipment): ResourcesInterface
    {
        // TODO: Get service rates for given shipment from carrier (use CarrierApiGateway).
        // TODO: Map data to ServiceRate objects. Convert timestamp to UTC.
        // TODO: Put ServiceRate objects in an object that implements ResourcesInterface
    }
}
