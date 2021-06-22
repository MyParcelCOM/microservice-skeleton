<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use MyParcelCom\Microservice\Carrier\Errors\Mappers\ErrorMapper;

class CarrierApiGateway implements CarrierApiGatewayInterface
{
    /** @var HttpClient */
    private $httpClient;

    /**
     * Constructor.
     * Note: Uses constructor dependency injection.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritDoc
     */
    public function get(string $endpoint, array $queryParams = [], array $headers = []): PromiseInterface
    {
        $options = $this->initRequestOptions($queryParams, $headers);

        return $this->httpClient
            ->getAsync($endpoint, $options)
            ->otherwise(function (RequestException $exception) {
                return $this->handleCarrierErrors($exception);
            });
    }

    /**
     * @inheritDoc
     */
    public function post(string $endpoint, array $data, array $queryParams = [], array $headers = []): PromiseInterface
    {
        $options = $this->initRequestOptions($queryParams, $headers, [
            RequestOptions::JSON => $data,
        ]);

        return $this->httpClient
            ->postAsync($endpoint, $options)
            ->otherwise(function (RequestException $exception) {
                return $this->handleCarrierErrors($exception);
            });
    }

    /**
     * @inheritDoc
     */
    public function setCredentials(array $credentials): CarrierApiGatewayInterface
    {
        // TODO: Store needed API credentials in one or more class variables

        return $this;
    }

    private function initRequestOptions(array $queryParams = [], array $headers = [], array $extraOptions = [])
    {
        return array_merge([
            RequestOptions::QUERY => $queryParams,
            RequestOptions::HEADERS => $headers,
        ], $extraOptions);
    }

    /**
     * @param GuzzleException $exception
     */
    private function handleCarrierErrors(RequestException $exception)
    {
        $response = $exception->getResponse();

        $mapper = new ErrorMapper();
        if ($mapper->hasErrors($response)) {
            $errors = $mapper->mapErrors($response);

            if ($errors) {
                $errors->setMeta([
                    'carrier_response' => json_encode((string) $response->getBody()),
                    'carrier_status'   => $response->getStatusCode(),
                ]);

                throw $errors;
            }
        }

        throw $exception;
    }
}
