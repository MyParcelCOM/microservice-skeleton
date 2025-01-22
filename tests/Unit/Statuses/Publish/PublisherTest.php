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
use MyParcelCom\Microservice\Tests\TestCase;

class PublisherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testItPublishesStatusesMessage(): void
    {
        $snsClient = Mockery::mock(SnsClient::class);
        $snsClient
            ->expects('publishBatchAsync')
            ->with([
                'PublishBatchRequestEntries' => [
                    [
                        'Id'      => 'test',
                        // Message is encoded json
                        'Message' => '{"origin":"test-origin","shipment_id":"test","status":{"data":{"type":"statuses","attributes":{"code":"test"}}},"postpone_poll":"PT1H2M3S"}',
                    ],
                ],
                'TopicArn'                   => 'test',
            ])
            ->andReturns(Mockery::mock(Promise::class));

        $transformerService = Mockery::mock(TransformerService::class);
        $transformerService
            ->expects('transformResource')
            ->with(Mockery::type(Status::class))
            ->andReturns([
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
                id: 'test',
                postponePoll: Mockery::mock(PostponePoll::class, ['serialize' => 'PT1H2M3S']),
                status: Mockery::mock(Status::class),
                shipmentId: 'test',
                origin: 'test-origin',
            ),
        );
    }
}
