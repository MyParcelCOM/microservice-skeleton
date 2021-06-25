<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use GuzzleHttp\Psr7\Response;

class MockedClientResponse
{
    /** @var string */
    protected $method;

    /** @var string */
    protected $uri;

    /** @var int */
    protected $code;

    /** @var array */
    protected $headers;

    /** @var string */
    protected $body;

    /**
     * @param string  $method
     * @param string  $uri
     * @param integer $code
     * @param string  $body
     * @param array   $headers
     */
    public function __construct(
        string $method,
        string $uri,
        int $code = 400,
        string $body = null,
        array $headers = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->code = $code;
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Response
     */
    public function toClientResponse(): Response
    {
        return new Response($this->code, $this->headers, $this->body);
    }
}
