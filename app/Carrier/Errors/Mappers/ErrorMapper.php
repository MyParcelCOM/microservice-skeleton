<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier\Errors\Mappers;

use MyParcelCom\JsonApi\Errors\GenericCarrierError;
use MyParcelCom\JsonApi\Exceptions\Interfaces\JsonSchemaErrorInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorMapper extends AbstractErrorMapper
{
    /**
     * Parse response and return array with error(s).
     * Every array element (in other words; every error) should
     * contain a "description" and "code" key. For example:
     * [
     *      [
     *          'description'   => 'Invalid email address',
     *          'code'          => '1337'
     *      ]
     * ]
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function extractErrorsFromResponse($response): array
    {
        // TODO: Check response and create errors if there are any

        return [];
    }

    /**
     * @param string $message
     * @param string $code
     * @param string $pointer
     * @return JsonSchemaErrorInterface
     */
    protected function mapError(string $message, string $code = '', string $pointer = ''): JsonSchemaErrorInterface
    {
        // TODO: Check error code and/or message and return appropriate error

        // Return a generic carrier error
        // if were unable to map the given code to a specific error
        return new GenericCarrierError($code, $message);
    }

    /**
     * Determines whether or not given response
     * contains errors that should be mapped.
     *
     * @param ResponseInterface $response
     * @return bool
     */
    public function hasErrors($response): bool
    {
        // TODO: Check response to see if there was an error

        return false;
    }
}
