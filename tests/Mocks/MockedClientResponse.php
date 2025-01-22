<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use GuzzleHttp\Psr7\Response;

class MockedClientResponse
{
    public function __construct(
        protected string $method,
        protected string $uri,
        protected int $code = 400,
        protected ?string $body = null,
        protected array $headers = [],
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function toClientResponse(): Response
    {
        return new Response($this->code, $this->headers, $this->body);
    }
}
