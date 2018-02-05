<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier;

use GuzzleHttp\Promise\PromiseInterface;

interface CarrierApiGatewayInterface
{
    /**
     * @param string $url
     * @param array  $queryParams
     * @param array  $headers
     * @return PromiseInterface
     */
    public function get(
        string $url, array $queryParams = [], array $headers = [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
    ]
    ): PromiseInterface;

    /**
     * @param string $url
     * @param array  $data
     * @param array  $queryParams
     * @param array  $headers
     * @return PromiseInterface
     */
    public function post(
        string $url, array $data, array $queryParams = [], array $headers = [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
    ]
    ): PromiseInterface;

    /**
     * @param array $credentials
     * @return $this
     */
    public function setCredentials(array $credentials): self;
}
