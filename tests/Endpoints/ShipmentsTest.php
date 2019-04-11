<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use Mockery;
use MyParcelCom\Hermes\Http\ShipmentRequest;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Tests\Mocks\ShipmentRequestMock;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * @group Endpoints:Shipment
 * @group Implementation
 */
class ShipmentsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();

        $this->app->singleton(ShipmentRequest::class, ShipmentRequestMock::class);
        $this->bindCarrierApiGatewayMock();
    }

    /** @test */
    public function testPostShipment()
    {
        $this->bindCarrierApiGatewayMock();

        // TODO: Add carrier response stub for creating a shipment.
        // See the "Response Stubs" chapter in the readme for more info.

        $data = \GuzzleHttp\json_decode(
            file_get_contents(base_path('tests/Stubs/shipment-request.stub')),
            true
        );

        $this->assertJsonSchema(
            '/shipments',
            '/shipments',
            $this->getRequestHeaders(),
            $data,
            'post',
            201
        );
    }

    /** @test */
    public function testItValidatesAShipmentRequestBasedOnValidationRules()
    {
        $this->bindCarrierApiGatewayMock();

        $data = \GuzzleHttp\json_decode(
            file_get_contents(base_path('tests/Stubs/shipment-request.stub')),
            true
        );

        $response = $this->json('post', '/shipments',$data, $this->getRequestHeaders());

        // TODO: Add assertions!
    }

    // TODO: Add test for when validation passes.

    // TODO: Add tests for when validation fails for each case :) (see ShipmentRequestMock for the rules)
}
