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

        $response = $this->json('GET', '/v1/validate-credentials', [], [
            'foo' => 'bar',
            'some' => 'credentials'
        ]);
        $response->assertStatus(400);
    }

    /** @test */
    public function testItReturnsASuccessfulResponseWithValidCredentials()
    {
        $this->bindCarrierApiGatewayMock();

        $response = $this->json('GET', '/v1/validate-credentials', [], $this->getRequestHeaders());
        $response->assertStatus(200);
    }

}
