<?php

namespace MyParcelCom\Microservice\Events;

class MakingCarrierApiRequest
{
    /**
     * Create a new event instance.
     *
     * @param string $context
     * @param string $url
     * @param string $method
     * @param mixed  $body
     */
    public function __construct(
        private string $url,
        private string $method,
        private string $context = 'Carrier API request',
        private $body = null
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
