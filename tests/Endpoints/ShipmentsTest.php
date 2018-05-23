<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use Mockery;
use MyParcelCom\JsonApi\Traits\AssertionsTrait;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

/**
 * @group Endpoints:Shipment
 * @group Implementation
 */
class ShipmentsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use AssertionsTrait;

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
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
            '/v1/shipments',
            $this->getRequestHeaders(),
            $data,
            'post',
            201
        );
    }
}
