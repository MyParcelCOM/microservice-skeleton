<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses\Publish;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Statuses\Publish\PostponePoll;
use MyParcelCom\Microservice\Statuses\Publish\StatusMessage;
use MyParcelCom\Microservice\Statuses\Status;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class StatusMessageTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testItSerializesStatusMessageWithIdShipmentIdPostponePollAndStatuses(): void
    {
        $id = Uuid::uuid4()->toString();
        $shipmentId = Uuid::uuid4()->toString();

        $statusesMessage = new StatusMessage(
            id: $id,
            postponePoll: Mockery::mock(PostponePoll::class, ['serialize' => 'PT1H2M3S']),
            status: Mockery::mock(Status::class),
            shipmentId: $shipmentId,
            origin: 'test-origin',
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
            'Id'      => $id,
            // message is json encoded string
            'Message' => "{\"origin\":\"test-origin\",\"shipment_id\":\"{$shipmentId}\",\"status\":{\"data\":{\"type\":\"statuses\",\"attributes\":{\"code\":\"test\"}}},\"postpone_poll\":\"PT1H2M3S\"}",
        ];

        self::assertEquals($expected, $statusesMessage->serialize($transformerService));
    }
}
