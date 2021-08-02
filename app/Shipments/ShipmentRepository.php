<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Interfaces\MapperInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class ShipmentRepository
{
    protected MapperInterface $shipmentMapper;
    protected CarrierApiGatewayInterface $carrierApiGateway;

    public function __construct(
        CarrierApiGatewayInterface $carrierApiGateway,
        ShipmentMapper $shipmentMapper,
    ) {
        $this->carrierApiGateway = $carrierApiGateway;
        $this->shipmentMapper = $shipmentMapper;
    }

    /**
     * Makes a shipment from the posted shipment data and persists it (by sending it to the carrier api).
     *
     * @param array $data
     * @param array $meta
     * @return Shipment
     */
    public function createFromPostData(array $data, array $meta = []): Shipment
    {
        /** @var Shipment $shipment */
        $shipment = $this->shipmentMapper->map($data, new Shipment());
        $shipment->setTrackTraceEnabled(Arr::get($meta, 'track_trace.enabled', true));

        // TODO: Validate the data for this specific carrier.
        // TODO: Map/transform the Shipment to a valid request for the carrier.
        // TODO: Send the shipment to the carrier (use CarrierApiGateway).
        // TODO: Map updated values to the Shipment (barcode, id, etc).
        // TODO: Get files (label, printcode, etc) for the shipment.
        // TODO: Add files to the shipment (use File objects).

        return $shipment;
    }
}
