<?php

namespace MyParcelCom\Microservice\Statuses\Publish;

use Aws\Sns\SnsClient;
use GuzzleHttp\Promise\Promise;
use MyParcelCom\JsonApi\Transformers\TransformerService;

class Publisher
{
    public function __construct(
        private readonly SnsClient $snsClient,
        private readonly TransformerService $transformerService
    ) {
    }

    /**
     * @param StatusesMessage $message
     * @return Promise
     */
    public function publish(StatusesMessage $message): Promise
    {
        return $this->snsClient->publishBatchAsync(
            $message->serialize($this->transformerService)
        );
    }
}
