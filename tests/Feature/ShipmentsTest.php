<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use Mockery;
use MyParcelCom\Common\Traits\JsonApiAssertionsTrait;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

class ShipmentsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function testPostShipment()
    {
        $this->bindCarrierApiGatewayMock();

        $data = \GuzzleHttp\json_decode(
            file_get_contents(
                base_path('tests/Stubs/shipment-request.json')
            ),
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

    /** @test */
    public function testPostInvalidShipment()
    {
        $this->bindCarrierApiGatewayMock();

        // TODO: Edit invalid-shipment-request.json to reflect invalid
        //       shipment according to carrier.
        $data = \GuzzleHttp\json_decode(
            file_get_contents(
                base_path('tests/Stubs/invalid-shipment-request.json')
            ),
            true
        );

        $this->assertJsonSchema(
            '/shipments',
            '/v1/shipments',
            $this->getRequestHeaders(),
            $data,
            'post',
            400
        );
    }
}
