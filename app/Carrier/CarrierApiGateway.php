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

        event(new MakingCarrierApiRequest($endpoint, 'get'));

        return $this->handleResponse(
            $this->httpClient->getAsync($endpoint, $options)
        );
    }

    /**
     * @inheritDoc
     */
    public function post(string $endpoint, array $data, array $queryParams = [], array $headers = []): PromiseInterface
    {
        $options = $this->initRequestOptions($queryParams, $headers, [
            RequestOptions::JSON => $data,
        ]);

        event(new MakingCarrierApiRequest($endpoint, 'post', body: $data));

        return $this->handleResponse(
            $this->httpClient->postAsync($endpoint, $options)
        );
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
            RequestOptions::QUERY   => $queryParams,
            RequestOptions::HEADERS => $headers,
        ], $extraOptions);
    }

    /**
     * @param ResponseInterface $response
     */
    private function handleCarrierErrors(ResponseInterface $response)
    {
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
    }

    /**
     * @param PromiseInterface $requestPromise
     * @return PromiseInterface
     * @throws LogicException
     */
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
