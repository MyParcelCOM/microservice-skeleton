<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class PickUpDropOffLocationRepository
{
    /** @var CarrierApiGatewayInterface */
    protected $carrierApiGateway;

    /**
     * @param string      $countryCode
     * @param string      $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @return ResourcesInterface
     */
    public function getAll(string $countryCode, string $postalCode, string $street = null, string $streetNumber = null): ResourcesInterface
    {
        // TODO: Get the pudo points from carrier (use CarrierApiGateway).
        // TODO: Map data to PickUpDropOffLocation objects.
        // TODO: Put PickUpDropOffLocation objects in an object that implements ResourcesInterface.

        // CollectionResources
        // PromiseResources
        // QueryResources
        // PromiseCollectionResources
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
