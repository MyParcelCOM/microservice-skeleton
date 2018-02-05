<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Traits;

use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;

trait CommunicatesWithCarrier
{
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
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        return [
            'X-MYPARCELCOM-SECRET'      => config('app.secret'),
            'X-MYPARCELCOM-CREDENTIALS' => \GuzzleHttp\json_encode($this->getApiCredentials()),
        ];
    }

    /**
     * Binds a mock object to the CarrierApiGatewayInterface in the dependency
     * container.
     */
    protected function bindCarrierApiGatewayMock()
    {
        $this->app->singleton(CarrierApiGatewayInterface::class, CarrierApiGatewayMock::class);
    }
}
