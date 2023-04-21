<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use Mockery;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use PHPUnit\Framework\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CarrierApiGatewayMock implements CarrierApiGatewayInterface
{
    /**
     * @inheritdoc
     */
    public function get(string $url, array $queryParams = [], array $headers = []): PromiseInterface
    {
        return Create::promiseFor(Mockery::mock(ResponseInterface::class, [
            'getBody' => $this->getResponseStub('get', $url),
            'getCode' => 200,
        ]));
    }

    private function getResponseStub(string $method, string $url): StreamInterface
    {
        $stubPath = base_path(
            'tests/Stubs/'
            . $method . '-' . str_replace('/', '-', $url)
            . '.stub'
        );

        if (!file_exists($stubPath)) {
            throw new Exception(
                sprintf(
                    'The stub response file `%s` does not exist. Please create it and add a response to it for a `%s` request to `%s`',
                    $stubPath,
                    $method,
                    $url
                )
            );
        }

        return Mockery::mock(StreamInterface::class, [
            '__toString' => file_get_contents($stubPath),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function post(string $url, array $data, array $queryParams = [], array $headers = []): PromiseInterface
    {
        return Create::promiseFor(Mockery::mock(ResponseInterface::class, [
            'getBody' => $this->getResponseStub('post', $url),
            'getCode' => 200,
        ]));
    }

    /**
     * @param array $credentials
     * @return $this
     */
    public function setCredentials(array $credentials): CarrierApiGatewayInterface
    {
        return $this;
    }
}
