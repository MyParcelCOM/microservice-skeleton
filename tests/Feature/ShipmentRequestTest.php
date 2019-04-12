<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use Mockery;
use MyParcelCom\Hermes\Http\ShipmentRequest;
use MyParcelCom\Microservice\Tests\Mocks\ShipmentRequestMock;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

class ShipmentRequestTest extends TestCase
{
    use CommunicatesWithCarrier;

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function testItValidatesAShipmentRequestBasedOnValidationRules()
    {
        $this->app->singleton(ShipmentRequest::class, ShipmentRequestMock::class);
        $this->bindCarrierApiGatewayMock();

        $data = \GuzzleHttp\json_decode(
            file_get_contents(base_path('tests/Stubs/shipment-request.stub')),
            true
        );

        $response = $this->json('post', '/shipments',$data, $this->getRequestHeaders());

        dd($response->json());
        // TODO: Add assertions!
    }

    // TODO: Add test for when validation passes.

    // TODO: Add tests for when validation fails for each case :) (see ShipmentRequestMock for the rules)
}
