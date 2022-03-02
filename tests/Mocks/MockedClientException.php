<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class MockedClientException extends MockedClientResponse
{
    /** @var string */
    protected $message;

    /**
     * @param string  $method
     * @param string  $uri
     * @param integer $code
     * @param string  $message
     * @param string  $body
     */
    public function __construct(
        string $method,
        string $uri,
        int $code = 400,
        string $message = null,
        string $body = null,
    ) {
        parent::__construct($method, $uri, $code, $body, []);
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return ClientException
     */
    public function toClientException(): ClientException
    {
        return new ClientException(
            $this->message ?? 'Unknown error',
            new Request($this->method, $this->uri),
            $this->toClientResponse()
        );
    }
}
