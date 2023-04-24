<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Events;

class CompletedCarrierApiRequest
{
    public function __construct(
        private readonly mixed $response = null,
    ) {
    }

    public function getResponse(): mixed
    {
        return $this->response;
    }
}
