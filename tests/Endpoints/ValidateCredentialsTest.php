<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * @group Endpoints:Shipment
 * @group Implementation
 */
class ValidateCredentialsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    /** @test */
    public function testItReturnsAnUnsuccessfulResponseWithInvalidCredentials()
    {
        $this->bindCarrierApiGatewayMock();

        $this->assertJsonSchema(
            '/validate-credentials',
            '/v1/validate-credentials',
            [
                'foo' => 'bar',
                'some' => 'credentials'
            ],
            [],
            'get',
            400
        );
    }

    /** @test */
    public function testItReturnsASuccessfulResponseWithValidCredentials()
    {
        $this->bindCarrierApiGatewayMock();

        $this->assertJsonSchema(
            '/validate-credentials',
            '/v1/validate-credentials',
            $this->getRequestHeaders(),
            [],
            'get',
            200
        );

        $response = $this->json('GET', '/v1/validate-credentials', [], $this->getRequestHeaders());
        $response->assertStatus(200);
    }

}
