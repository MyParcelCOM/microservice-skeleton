<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use MyParcelCom\Common\Contracts\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class StatusRepository
{
    /** @var CarrierApiGatewayInterface */
    protected $carrierApiGateway;

    /**
     * @param string $shipmentId
     * @param string $trackingCode
     * @return ResourcesInterface
     */
    public function getStatuses(string $shipmentId, string $trackingCode): ResourcesInterface
    {
        // TODO: Get statuses for given shipment/tracking_code from carrier (use CarrierApiGateway).
        // TODO: Map data to Status objects.
        // TODO: Put Status objects in an object that implements ResourcesInterface
    }

    /**
     * @param CarrierApiGatewayInterface $gateway
     * @return $this
     */
    public function setCarrierApiGateway(CarrierApiGatewayInterface $gateway): self
    {
        $this->carrierApiGateway = $gateway;

        return $this;
    }
}
