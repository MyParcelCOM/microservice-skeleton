<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses\Publish;

use Mockery;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Statuses\Publish\PostponePoll;
use MyParcelCom\Microservice\Statuses\Publish\StatusesMessage;
use MyParcelCom\Microservice\Statuses\Status;
use MyParcelCom\Microservice\Tests\TestCase;
use Ramsey\Uuid\Uuid;

class StatusesMessageTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testItSerializesStatusesMessageWithShipmentIdPostponePollAndStatuses(): void
    {
        $shipmentId = Uuid::uuid4()->toString();

        $statusesMessage = new StatusesMessage(
            $shipmentId,
            Mockery::mock(PostponePoll::class, ['serialize' => 'PT1H2M3S']),
            Mockery::mock(Status::class),
        );

        $transformerService = Mockery::mock(TransformerService::class);
        $transformerService
            ->shouldReceive('transformResource')
            ->once()
            ->with(Mockery::type(Status::class))
            ->andReturn([
                'data' => [
                    'type'       => 'statuses',
                    'attributes' => [
                        'code' => 'test',
                    ],
                ],
            ]);

        $expected = [
            [
                'MessageGroupId' => env('APP_NAME'),
                'Message'        => [
                    'shipment_id'   => $shipmentId,
                    'status'        => [
                        'data' => [
                            'type'       => 'statuses',
                            'attributes' => [
                                'code' => 'test',
                            ],
                        ],
                    ],
                    'postpone_poll' => 'PT1H2M3S',
                ],
            ],
        ];

        self::assertEquals($expected, $statusesMessage->serialize($transformerService));
    }
}
