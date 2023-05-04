<?php

namespace MyParcelCom\Microservice\Statuses;

use Aws\Sns\SnsClient;
use GuzzleHttp\Promise\Promise;
use MyParcelCom\Microservice\Shipments\Shipment;
use function array_map;

class StatusPublisher
{
    public function __construct(private readonly SnsClient $snsClient)
    {
    }

    /**
     * @param Status[] $statuses
     * @return Promise
     */
    public function publish(array $statuses): Promise
    {
        return $this->snsClient->publishBatchAsync($this->formatMessages($statuses));
    }

    /**
     * @param Status[] $statuses
     * @return array
     */
    private function formatMessages(array $statuses): array
    {
        return array_map(function (Status $status) {
            $shipmentId = $status->shipment()->first()->getId();

            return [
                'MessageGroupId' => env('APP_NAME'),
                'Message'        => [
                    'shipment_id'   => $shipmentId,
                    'status'        => $status,
                    'postpone_poll' => false,
                ],
            ];
        }, $statuses);
    }
}
