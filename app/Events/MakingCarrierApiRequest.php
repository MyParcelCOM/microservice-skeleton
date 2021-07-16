<?php

namespace MyParcelCom\Microservice\Events;

class MakingCarrierApiRequest
{
    private string $url;
    private string $method;
    private string $context;

    /**
     * Create a new event instance.
     *
     * @param string $context
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method, string $context = 'Carrier API request')
    {
        $this->url = $url;
        $this->method = $method;
        $this->context = $context;
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
}
