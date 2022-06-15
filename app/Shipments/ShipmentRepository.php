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
        $shipment->setLabelMimeType(Arr::get($meta, 'label.mime_type', Shipment::LABEL_MIME_TYPE_PDF));
        $shipment->setLabelSize(Arr::get($meta, 'label.size', Shipment::LABEL_SIZE_A6));

        // TODO: Validate the data for this specific carrier.
        // TODO: Map/transform the Shipment to a valid request for the carrier.
        // TODO: Send the shipment to the carrier (use CarrierApiGateway).
        // TODO: Map updated values to the Shipment (barcode, id, etc).
        // TODO: Get files (label, printcode, etc) for the shipment.
        // TODO: Add files to the shipment (use File objects).

        return $shipment;
    }


    /**
     * Makes a multi-colli shipment from the posted shipment data and persists it (by uploading it to the carrier FTP).
     *
     * @param array $data
     * @param array $meta
     * @return array
     */
    public function createFromMultiColliPostData(array $data, array $meta = []): array
    {
        // Todo: append to the logic in the initShipment method to create a multi-colli shipment with the carrier.
        $master = $this->initShipment($data['master'], $meta, true);

        $colli = collect($data['colli'])->map(function (array $shipmentData, int $index) use ($meta) {
            $shipment = $this->initShipment($shipmentData, $meta);
            return $shipment->setColloNumber($index + 1);
        });

        // Todo: uncomment related methods in the shipment resource
        // Link colli shipments to master shipment (and update changes)
        $master->colli()->saveMany($colli);

        return [
            'master' => $master,
            'colli'  => $colli,
        ];
    }

    /**
     * @param array $shipmentData
     * @param array $meta
     * @param bool  $isMaster
     * @return Shipment
     */
    private function initShipment(array $shipmentData, array $meta, bool $isMaster = false): Shipment
    {
        $shipment = $this->shipmentMapper->map($shipmentData, new Shipment());
        $shipment->setLabelMimeType(Arr::get($meta, 'label.mime_type', Shipment::LABEL_MIME_TYPE_PDF));
        $shipment->setTrackTraceEnabled(Arr::get($meta, 'track_trace.enabled', true));

        // Todo: Set needed values on shipment from credentials

        // Don't automatically set a sequence number when it's a master shipment
        // Because a label is not needed for a master shipment
        // This will also prevent gaps in the label sequence numbering
        if ($isMaster) {
            $shipment->setSequenceNumber('MASTER');
        }

        // Todo: Store shipment in DB so a UUID is created for the shipment
        // $shipment->save();

        // Todo: Map calculated data back into the Shipment object in the carrier mapper

        return $shipment;
    }
}
