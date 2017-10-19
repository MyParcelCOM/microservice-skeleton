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
     * @param string $barcode
     * @return ResourcesInterface
     */
    public function getStatuses(string $shipmentId, string $barcode): ResourcesInterface
    {
        // TODO Get statuses for given shipment/barcode from carrier (use CarrierApiGateway).
        // TODO Map data to Status objects.
        // TODO Put Status objects in an object that implements ResourcesInterface
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
