<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

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

        $response = $this->json('POST', '/validate-credentials', $data);
        $response->assertStatus(400);
    }

    /** @test */
    public function testItReturnsASuccessfulResponseWithValidCredentials()
    {
        $this->bindCarrierApiGatewayMock();

        $data = [
            'credentials' => config('services.carrier_credentials')
        ];

        $response = $this->json('POST', '/validate-credentials', $data);
        $response->assertStatus(200);
    }

}
