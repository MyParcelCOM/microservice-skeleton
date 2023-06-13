<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses\Publish;

use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Statuses\Status;

class StatusesMessage
{
    /**
     * @var Status[]
     */
    public readonly array $statuses;

    public function __construct(
        public readonly string $shipmentId,
        public readonly PostponePoll $postponePoll,
        Status ...$statuses,
    ) {
        $this->statuses = $statuses;
    }

    public function serialize(TransformerService $transformerService): array
    {
        return array_map(fn(Status $status) => [
            'MessageGroupId' => config('app.name'),
            'Message'        => [
                'shipment_id'   => $this->shipmentId,
                'status'        => $transformerService->transformResource($status),
                'postpone_poll' => $this->postponePoll->serialize()
            ],
        ], $this->statuses);
    }
}
