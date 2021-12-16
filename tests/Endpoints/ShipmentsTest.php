<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use GuzzleHttp\Utils;
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

    /** @test */
    public function testPostShipment()
    {
        $this->bindHttpClientMock();

        // TODO: Add carrier response stub for creating a shipment.
        // See the "Response Stubs" chapter in the readme for more info.

        $requestStub = file_get_contents(base_path('tests/Stubs/shipment-request.json'));
        $data = Utils::jsonDecode($requestStub, true);

        $this->assertJsonSchema(
            '/shipments',
            '/shipments',
            $this->getRequestHeaders(),
            $data,
            'post',
            201
        );
    }
}
