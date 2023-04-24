<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier;

use GuzzleHttp\Promise\PromiseInterface;

interface CarrierApiGatewayInterface
{
    public function get(string $url, array $queryParams = [], array $headers = []): PromiseInterface;

    public function post(string $url, array $data, array $queryParams = [], array $headers = []): PromiseInterface;

    public function setCredentials(array $credentials): self;
}
