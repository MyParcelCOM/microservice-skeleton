<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses\Publish;

use Aws\Sns\SnsClient;
use GuzzleHttp\Promise\Promise;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Statuses\Publish\PostponePoll;
use MyParcelCom\Microservice\Statuses\Publish\Publisher;
use MyParcelCom\Microservice\Statuses\Publish\StatusMessage;
use MyParcelCom\Microservice\Statuses\Status;
use PHPUnit\Framework\TestCase;

class PublisherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testItPublishesStatusesMessage(): void
    {
        $snsClient = Mockery::mock(SnsClient::class);
        $snsClient
            ->shouldReceive('publishBatchAsync')
            ->once()
            ->with([
                'PublishBatchRequestEntries' => [
                    [
                        'Id'             => 'test',
                        // Message is encoded json
                        'Message'        => '{"shipment_id":"test","status":{"data":{"type":"statuses","attributes":{"code":"test"}}},"postpone_poll":"PT1H2M3S"}',
                    ],
                ],
                'TopicArn'                   => 'test',
            ])
            ->andReturn(Mockery::mock(Promise::class));

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

        $publisher = new Publisher($snsClient, $transformerService);

        $publisher->publish(
            'test',
            new StatusMessage(
                'test',
                'test',
                Mockery::mock(PostponePoll::class, ['serialize' => 'PT1H2M3S']),
                Mockery::mock(Status::class),
            )
        );
    }
}
