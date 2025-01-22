<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class MockedClientException extends MockedClientResponse
{
    public function __construct(
        string $method,
        string $uri,
        int $code = 400,
        protected ?string $message = null,
        string $body = null,
    ) {
        parent::__construct($method, $uri, $code, $body);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function toClientException(): ClientException
    {
        return new ClientException(
            $this->message ?? 'Unknown error',
            new Request($this->method, $this->uri),
            $this->toClientResponse(),
        );
    }
}
