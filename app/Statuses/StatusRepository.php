<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class StatusRepository
{
    protected CarrierApiGatewayInterface $carrierApiGateway;

    public function __construct(
        CarrierApiGatewayInterface $carrierApiGateway,
    ) {
        $this->carrierApiGateway = $carrierApiGateway;
    }

    /**
     * @param string $shipmentId
     * @param string $trackingCode
     * @return ResourcesInterface
     */
    public function getStatuses(string $shipmentId, string $trackingCode): ResourcesInterface
    {
        // TODO: Get statuses for given shipment/tracking_code from carrier (use CarrierApiGateway).
        // TODO: Map data to Status objects. Convert timestamp to UTC.
        // TODO: Put Status objects in an object that implements ResourcesInterface
    }
}
