<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

/**
 * @group Endpoints:Shipment
 * @group Implementation
 */
class ValidateCredentialsTest extends TestCase
{
    use CommunicatesWithCarrier;

    /** @test */
    public function testItReturnsAnUnsuccessfulResponseWithInvalidCredentials()
    {
        $this->bindCarrierApiGatewayMock();

        $data = [
            'credentials' => [
                'foo' => 'bar'
            ]
        ];

        $response = $this->json('POST', '/v1/validate-credentials', $data, $this->getRequestHeaders());
        $response->assertStatus(400);
    }

    /** @test */
    public function testItReturnsASuccessfulResponseWithValidCredentials()
    {
        $this->bindCarrierApiGatewayMock();

        $data = [
            'credentials' => config('services.carrier_credentials')
        ];

        $response = $this->json('POST', '/v1/validate-credentials', $data, $this->getRequestHeaders());
        $response->assertStatus(200);
    }

}
