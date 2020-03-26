<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MyParcelCom\JsonApi\Errors\InvalidInputError;
use MyParcelCom\JsonApi\Errors\MissingInputError;
use MyParcelCom\JsonApi\ExceptionHandler;
use MyParcelCom\JsonApi\Exceptions\CarrierApiException;
use MyParcelCom\JsonApi\Exceptions\Interfaces\MultiErrorInterface;
use MyParcelCom\JsonApi\Exceptions\InvalidInputException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    public const ALLOWED_STATUS_CODES = [
        Response::HTTP_BAD_REQUEST,
        Response::HTTP_UNAUTHORIZED,
        Response::HTTP_NOT_FOUND,
        Response::HTTP_NOT_ACCEPTABLE,
        Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        Response::HTTP_UNPROCESSABLE_ENTITY,
        Response::HTTP_INTERNAL_SERVER_ERROR,
        Response::HTTP_SERVICE_UNAVAILABLE,
    ];

    /**
     * @param Request   $request
     * @param Exception $exception
     * @return JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof RequestException && ($response = $exception->getResponse()) !== null) {
            $carrierResponse = json_decode($response->getBody()->getContents(), true)
                ?? ['response_body' => (string) $response->getBody()];

            $exception = new CarrierApiException(
                $this->mapStatusCode($response->getStatusCode()),
                $carrierResponse,
                $exception
            );
        }

        if ($exception instanceof MultiErrorInterface && $this->newrelic) {
            foreach ($exception->getErrors() as $index => $error) {
                $errorNo = $index + 1;
                $this->newrelic->addCustomParameter("response.error_${errorNo}", $error->getDetail());
            }
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->mapValidationException($exception);
        }

        return parent::render($request, $exception);
    }

    public function report(Exception $e): void
    {
        if ($this->shouldntReport($e)) {
            if ($this->newrelic) {
                $this->newrelic->ignoreTransaction();
                $this->newrelic->ignoreApdex();
            }
            return;
        }

        if (!config('newrelic.auto_enable') && $this->newrelic) {
            $this->newrelic->startTransaction(config('app.name'));
        }

        parent::report($e);
    }

    /**
     * @param $statusCode
     * @return int
     */
    private function mapStatusCode($statusCode)
    {
        // When allowed, use the carrier status code.
        if (in_array((int) $statusCode, self::ALLOWED_STATUS_CODES)) {
            return $statusCode;
        }

        // Map 4xx to 400 status code.
        if ($statusCode > 399 && $statusCode < 500) {
            return Response::HTTP_BAD_REQUEST;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @param ValidationException $exception
     * @return InvalidInputException
     */
    private function mapValidationException(ValidationException $exception): InvalidInputException
    {
        $validator = $exception->validator;

        $invalidAttributes = array_keys($validator->failed());

        $errors = [];

        foreach ($invalidAttributes as $invalidAttribute) {
            $errorMessages = $validator->errors()->get($invalidAttribute);

            foreach ($errorMessages as $errorMessage) {
                $pointer = str_replace('.', '/', $invalidAttribute);

                if (strpos($errorMessage, 'required') !== false) {
                    $errors[] = new MissingInputError('', $errorMessage, $pointer);
                } else {
                    $errors[] = new InvalidInputError('', $errorMessage, $pointer);
                }
            }
        }

        return new InvalidInputException($errors);
    }
}
