<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses\Publish;

use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Statuses\Status;

class StatusMessage
{
    public function __construct(
        public readonly string $id,
        public readonly PostponePoll $postponePoll,
        public readonly Status $status,
        public readonly ?string $shipmentId = null,
        public readonly ?string $trackingCode = null,
        public readonly ?string $myParcelComShipmentId = null,
        public readonly ?string $origin = null,
    ) {
    }

    public function serialize(TransformerService $transformerService): array
    {
        $message = array_filter([
            'origin'                  => $this->origin ?? config('app.name'),
            'shipment_id'             => $this->shipmentId,
            'tracking_code'           => $this->trackingCode,
            'myparcelcom_shipment_id' => $this->myParcelComShipmentId,
            'status'                  => $transformerService->transformResource($this->status),
            'postpone_poll'           => $this->postponePoll->serialize(),
        ]);

        return [
            'Id'      => $this->id,
            'Message' => json_encode($message, JSON_THROW_ON_ERROR),
        ];
    }
}
