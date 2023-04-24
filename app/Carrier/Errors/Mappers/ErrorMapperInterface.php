<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier\Errors\Mappers;

use MyParcelCom\JsonApi\Exceptions\Interfaces\MultiErrorInterface;

interface ErrorMapperInterface
{
    /**
     * Determines if a given response contains errors that should be mapped.
     *
     * @param mixed $response
     * @return bool
     */
    public function hasErrors($response): bool;

    /**
     * Parses errors in given response and maps it to a MultiErrorInterface.
     *
     * @param mixed $response
     * @return MultiErrorInterface|null
     */
    public function mapErrors($response): ?MultiErrorInterface;
}
