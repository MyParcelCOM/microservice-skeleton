<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier\Errors\Mappers;

use MyParcelCom\JsonApi\Errors\CarrierDataNotFoundError;
use MyParcelCom\JsonApi\Errors\InvalidCredentialsError;
use MyParcelCom\JsonApi\Errors\InvalidInputContextError;
use MyParcelCom\JsonApi\Errors\InvalidInputError;
use MyParcelCom\JsonApi\Errors\MissingInputError;
use MyParcelCom\JsonApi\Exceptions\CarrierDataNotFoundException;
use MyParcelCom\JsonApi\Exceptions\GenericCarrierException;
use MyParcelCom\JsonApi\Exceptions\Interfaces\JsonSchemaErrorInterface;
use MyParcelCom\JsonApi\Exceptions\Interfaces\MultiErrorInterface;
use MyParcelCom\JsonApi\Exceptions\InvalidCredentialsException;
use MyParcelCom\JsonApi\Exceptions\InvalidInputException;

abstract class AbstractErrorMapper implements ErrorMapperInterface
{
    /**
     * Parses errors in given response and maps it to a MultiErrorInterface.
     *
     * @param mixed $response
     * @return MultiErrorInterface|null
     */
    public function mapErrors($response): ?MultiErrorInterface
    {
        if (!$this->hasErrors($response)) {
            return null;
        }

        $errorMessages = $this->extractErrorsFromResponse($response);

        /** @var JsonSchemaErrorInterface[] $errors */
        $errors = [];

        foreach ($errorMessages as $error) {
            $this->addError($errors, $error['description'], $error['code']);
        }

        if (empty($errors)) {
            return null;
        }

        return $this->determineException($errors);
    }

    /**
     * Parse response and return array with error(s).
     * Every array element (in other words; every error) should contain a "description" and "code" key. For example:
     * [
     *      [
     *          'description'   => 'Invalid email address',
     *          'code'          => '1337'
     *      ]
     * ]
     *
     * @param mixed $response
     * @return array
     */
    abstract protected function extractErrorsFromResponse($response): array;

    /**
     * @param JsonSchemaErrorInterface[] $errors
     * @return MultiErrorInterface
     */
    protected function determineException(array $errors): MultiErrorInterface
    {
        $errorClasses = [];
        // Gather classes of errors found
        foreach ($errors as $error) {
            $errorClass = get_class($error);
            if (!in_array($errorClass, $errorClasses)) {
                $errorClasses[] = $errorClass;
            }
        }

        // If we have a credentials error
        if (in_array(InvalidCredentialsError::class, $errorClasses)) {
            return new InvalidCredentialsException($errors);
        }

        // If the request contains a not found error
        if (in_array(CarrierDataNotFoundError::class, $errorClasses)) {
            return new CarrierDataNotFoundException($errors);
        }

        // If the request contains input errors
        if (in_array(InvalidInputContextError::class, $errorClasses)
            || in_array(InvalidInputError::class, $errorClasses)
            || in_array(MissingInputError::class, $errorClasses)
        ) {
            return new InvalidInputException($errors);
        }

        return new GenericCarrierException($errors);
    }

    /**
     * @param JsonSchemaErrorInterface[] $errors
     * @param string                     $message
     * @param string                     $code
     * @return self
     */
    protected function addError(array &$errors, string $message, string $code = ''): self
    {
        $error = $this->mapError($message, $code);
        if ($error === null) {
            return $this;
        }

        $errors[] = $error;

        return $this;
    }

    /**
     * @param string $message
     * @param string $code
     * @return JsonSchemaErrorInterface
     */
    abstract protected function mapError(string $message, string $code = ''): JsonSchemaErrorInterface;
}
