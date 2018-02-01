<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\Common\Contracts\MapperInterface;
use MyParcelCom\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class ShipmentRepository
{
    /** @var MapperInterface */
    protected $shipmentMapper;

    /** @var CarrierApiGatewayInterface */
    protected $carrierApiGateway;

    /** @var ShipmentValidator */
    protected $shipmentValidator;

    /**
     * Makes a shipment and persists it (by sending it to the PostNL api)
     * from the shipment data posted.
     *
     * @param  array $data
     * @return Shipment
     * @throws InvalidJsonSchemaException
     */
    public function createFromPostData(array $data): Shipment
    {
        /** @var Shipment $shipment */
        $shipment = $this->shipmentMapper->map($data, new Shipment());

        // TODO Edit ShipmentValidator to include carrier-specific requirements.
        if (($errors = $this->shipmentValidator->validate($shipment))) {
            throw new InvalidJsonSchemaException($errors);
        }

        // TODO Map/transform the Shipment to a valid request for the carrier.
        // TODO Send the shipment to the carrier (use CarrierApiGateway).
        // TODO Map updated values to the Shipment (barcode, id, price, etc).
        // TODO Get files (label, printcode, etc) for the shipment.
        // TODO Add files to the shipment (use File objects).

        return $shipment;
    }

    /**
     * @param CarrierApiGatewayInterface $carrierApiGateway
     * @return $this
     */
    public function setCarrierApiGateway(CarrierApiGatewayInterface $carrierApiGateway): self
    {
        $this->carrierApiGateway = $carrierApiGateway;

        return $this;
    }

    /**
     * Set mapper to use when mapping request data to a Shipment.
     *
     * @param MapperInterface $mapper
     * @return $this
     */
    public function setShipmentMapper(MapperInterface $mapper): self
    {
        $this->shipmentMapper = $mapper;

        return $this;
    }

    /**
     * @param ShipmentValidator $validator
     * @return $this
     */
    public function setShipmentValidator(ShipmentValidator $validator): self
    {
        $this->shipmentValidator = $validator;

        return $this;
    }
}
