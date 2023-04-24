<?php

namespace MyParcelCom\Microservice\Events;

class MakingCarrierApiRequest
{
    public function __construct(
        private readonly string $url,
        private readonly string $method,
        private readonly string $context = 'Carrier API request',
        private readonly mixed $body = null,
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getBody()
    {
        return $this->body;
    }
}
