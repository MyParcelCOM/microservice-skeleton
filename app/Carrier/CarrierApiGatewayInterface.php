<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier;

use GuzzleHttp\Promise\PromiseInterface;

interface CarrierApiGatewayInterface
{
    /**
     * @param string $url
     * @param array  $queryParams
     * @return PromiseInterface
     */
    public function get(string $url, array $queryParams = []): PromiseInterface;

    /**
     * @param string $url
     * @param array  $data
     * @param array  $queryParams
     * @return PromiseInterface
     */
    public function post(string $url, array $data, array $queryParams = []): PromiseInterface;

    /**
     * @param array $credentials
     * @return CarrierApiGatewayInterface
     */
    public function setCredentials(array $credentials): self;
}
