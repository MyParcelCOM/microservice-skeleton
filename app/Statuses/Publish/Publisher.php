<?php

namespace MyParcelCom\Microservice\Statuses\Publish;

use Aws\Sns\SnsClient;
use GuzzleHttp\Promise\Promise;
use MyParcelCom\JsonApi\Transformers\TransformerService;

class Publisher
{
    public function __construct(
        private readonly SnsClient $snsClient,
        private readonly TransformerService $transformerService,
    ) {
    }

    public function publish(string $topicArn, StatusMessage ...$messages): Promise
    {
        return $this->snsClient->publishBatchAsync([
            'PublishBatchRequestEntries' => array_map(
                fn (StatusMessage $message) => $message->serialize($this->transformerService),
                $messages,
            ),
            'TopicArn'                   => $topicArn,
        ]);
    }
}
