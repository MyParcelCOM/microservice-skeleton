<?php

namespace MyParcelCom\Microservice\Statuses;

use Aws\Sns\SnsClient;
use GuzzleHttp\Promise\Promise;
use MyParcelCom\JsonApi\Transformers\TransformerException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Shipments\Shipment;
use function array_map;

readonly class StatusPublisher
{
    public function __construct(
        private SnsClient $snsClient,
        private TransformerService $transformerService
    ) {
    }

    /**
     * @param Status[] $statuses
     * @return Promise
     * @throws TransformerException
     */
    public function publish(array $statuses): Promise
    {
        return $this->snsClient->publishBatchAsync($this->formatMessages($statuses));
    }

    /**
     * @param Status[] $statuses
     * @return array
     * @throws TransformerException
     */
    private function formatMessages(array $statuses): array
    {
        return array_map(function (Status $status) {
            $shipmentId = $status->shipment()->first()->getId();

            return [
                'MessageGroupId' => env('APP_NAME'),
                'Message'        => [
                    'shipment_id'   => $shipmentId,
                    'status'        => $this->transformerService->transformResource($status),
                    'postpone_poll' => false,
                ],
            ];
        }, $statuses);
    }
}
