<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Exceptions;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MyParcelCom\JsonApi\Errors\InvalidInputError;
use MyParcelCom\JsonApi\Errors\MissingInputError;
use MyParcelCom\JsonApi\ExceptionHandler;
use MyParcelCom\JsonApi\Exceptions\CarrierApiException;
use MyParcelCom\JsonApi\Exceptions\InvalidInputException;
use MyParcelCom\Microservice\Events\ExceptionOccurred;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        // Fire an event to let the rest of the application know that an exception has occurred.
        // Primary usecase is to close any lingering Jaeger spans,
        // that couldn't be closed because an exception interrupted the flow.
        event(ExceptionOccurred::class);

        if ($exception instanceof RequestException && ($response = $exception->getResponse()) !== null) {
            $carrierResponse = json_decode((string) $response->getBody(), true)
                ?? ['response_body' => (string) $response->getBody()];

            $exception = new CarrierApiException(
                $this->mapStatusCode($response->getStatusCode()),
                $carrierResponse,
                $exception
            );
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->mapValidationException($exception);
        }

        return parent::render($request, $exception);
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
