<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Carrier;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use MyParcelCom\Microservice\Carrier\Errors\Mappers\ErrorMapper;
use MyParcelCom\Microservice\Events\FailedCarrierApiRequest;
use MyParcelCom\Microservice\Events\MakingCarrierApiRequest;
use MyParcelCom\Microservice\Events\SuccessfulCarrierApiRequest;
use Psr\Http\Message\ResponseInterface;

class CarrierApiGateway implements CarrierApiGatewayInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function get(string $endpoint, array $queryParams = [], array $headers = []): PromiseInterface
    {
        $options = $this->initRequestOptions($queryParams, $headers);

        event(new MakingCarrierApiRequest($endpoint, 'get'));

        return $this->handleResponse(
            $this->httpClient->getAsync($endpoint, $options),
        );
    }

    public function post(string $endpoint, array $data, array $queryParams = [], array $headers = []): PromiseInterface
    {
        $options = $this->initRequestOptions($queryParams, $headers, [
            RequestOptions::JSON => $data,
        ]);

        event(new MakingCarrierApiRequest($endpoint, 'post', body: $data));

        return $this->handleResponse(
            $this->httpClient->postAsync($endpoint, $options),
        );
    }

    public function setCredentials(array $credentials): CarrierApiGatewayInterface
    {
        // TODO: Store needed API credentials in one or more class variables

        return $this;
    }

    private function initRequestOptions(array $queryParams = [], array $headers = [], array $extraOptions = [])
    {
        return array_merge([
            RequestOptions::QUERY   => $queryParams,
            RequestOptions::HEADERS => $headers,
        ], $extraOptions);
    }

    private function handleCarrierErrors(ResponseInterface $response)
    {
        $mapper = new ErrorMapper();

        if ($mapper->hasErrors($response)) {
            $errors = $mapper->mapErrors($response);

            if ($errors) {
                $body = (string) $response->getBody();
                $errors->setMeta([
                    'carrier_response' => json_decode($body) ?? $body,
                    'carrier_status'   => $response->getStatusCode(),
                ]);

                throw $errors;
            }
        }
    }

    private function handleResponse(PromiseInterface $requestPromise): PromiseInterface
    {
        return $requestPromise
            ->then(function (ResponseInterface $response) {
                $this->handleCarrierErrors($response);
                event(new SuccessfulCarrierApiRequest());

                return $response;
            })
            ->otherwise(function (Exception $exception) {
                if ($exception instanceof RequestException) {
                    $response = $exception->getResponse();
                    event(new FailedCarrierApiRequest((string) $response->getBody()));
                    $this->handleCarrierErrors($response);
                } else {
                    event(new FailedCarrierApiRequest());
                }
                throw $exception;
            });
    }
}
