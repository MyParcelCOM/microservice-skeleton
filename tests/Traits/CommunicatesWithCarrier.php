<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Utils;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use MyParcelCom\Microservice\Tests\Mocks\HttpClientMock;
use MyParcelCom\Microservice\Tests\Mocks\MockedClientResponse;

trait CommunicatesWithCarrier
{
    /**
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        return [
            'X-MYPARCELCOM-SECRET'      => config('app.secret'),
            'X-MYPARCELCOM-CREDENTIALS' => Utils::jsonEncode($this->getApiCredentials()),
        ];
    }

    /**
     * Get credentials used for authentication with the carrier.
     *
     * @return array
     */
    protected function getApiCredentials(): array
    {
        return config('services.carrier_credentials');
    }

    /**
     * Binds a mock HttpClient in the service container.
     * Also prepares mocked HTTP requests and exceptions.
     *
     * @param MockedClientResponse[] $mockedClientResponses
     */
    protected function bindHttpClientMock(array $mockedClientResponses = [])
    {
        $this->app->singleton(HttpClient::class, function () use ($mockedClientResponses) {
            $httpClient = new HttpClientMock();
            foreach ($mockedClientResponses as $mockedResponse) {
                $httpClient->mockClientResponse($mockedResponse);
            }
            return $httpClient;
        });
    }

    /**
     * Binds a mock object to the CarrierApiGatewayInterface in the dependency container.
     */
    protected function bindCarrierApiGatewayMock()
    {
        $this->app->singleton(CarrierApiGatewayInterface::class, CarrierApiGatewayMock::class);
    }
}
