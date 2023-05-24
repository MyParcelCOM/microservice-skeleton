<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use Aws\Sns\SnsClient;
use GuzzleHttp\Promise\Promise;
use Mockery;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Statuses\Status;
use MyParcelCom\Microservice\Statuses\StatusPublisher;
use MyParcelCom\Microservice\Tests\TestCase;

class StatusPublisherTest extends TestCase
{
    private StatusPublisher $statusPublisher;
    private SnsClient $snsClient;

    protected function setUp(): void
    {
        parent::setUp();

        $transformerService = Mockery::mock(TransformerService::class);
        $transformerService->shouldReceive('transformResource')->andReturn([
            'shipment_id' => 'shipment-id',
            'status'      => [
                'id'          => 'status-id',
                'code'        => 'status-code',
                'description' => 'status-description',
                'category'    => 'status-category',
                'timestamp'   => 123456789,
            ],
        ]);

        $this->snsClient = Mockery::mock(SnsClient::class);
        $this->statusPublisher = new StatusPublisher($this->snsClient, $transformerService);
    }

    /** @test */
    public function itShouldTransformAStatusMessage(): void
    {
        $status = Mockery::mock(Status::class, [
            'id'          => 'status-id',
            'code'        => 'status-code',
            'description' => 'status-description',
            'category'    => 'status-category',
            'timestamp'   => 123456789,
        ]);

        $status->shouldReceive('shipment->first->getId')->once()->andReturn('shipment-id');

        $this->snsClient->shouldReceive('publishBatchAsync')
            ->once()
            ->with([
                [
                    'MessageGroupId' => config('app.name'),
                    'Message'        => [
                        'shipment_id'   => 'shipment-id',
                        'status'        => [
                            'shipment_id' => 'shipment-id',
                            'status'      => [
                                'id'          => 'status-id',
                                'code'        => 'status-code',
                                'description' => 'status-description',
                                'category'    => 'status-category',
                                'timestamp'   => 123456789,
                            ],
                        ],
                        'postpone_poll' => false,
                    ]
                ]
            ])
            ->andReturn(new Promise());
        $this->statusPublisher->publish([$status]);
    }
}
