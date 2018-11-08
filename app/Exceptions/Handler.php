<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;
use MyParcelCom\JsonApi\ExceptionHandler;
use MyParcelCom\JsonApi\Exceptions\CarrierApiException;
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
     * @param \Illuminate\Http\Request $request
     * @param Exception                $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof RequestException && ($response = $exception->getResponse()) !== null) {
            $carrierResponse = json_decode($response->getBody()->getContents(), true)
                ?? ['response_body' => (string)$response->getBody()];

            $exception = new CarrierApiException(
                $this->mapStatusCode($response->getStatusCode()),
                $carrierResponse,
                $exception
            );
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
        if (in_array((int)$statusCode, self::ALLOWED_STATUS_CODES)) {
            return $statusCode;
        }

        // Map 4xx to 400 status code.
        if ($statusCode > 399 && $statusCode < 500) {
            return Response::HTTP_BAD_REQUEST;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
