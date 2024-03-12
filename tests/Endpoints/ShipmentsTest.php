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
            201,
        );
    }

    /** @test */
    public function testPostValidMultiColliShipment()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        // Retrieve a valid request
        $multiColliStub = file_get_contents(base_path('tests/Stubs/shipment-request-multi-colli.json'));

        // Get data from JSON stub
        $data = json_decode($multiColliStub, true);

        // Check the schema and status code
        $response = $this->assertJsonSchema(
            '/multi-colli-shipments',
            '/multi-colli-shipments',
            $this->getRequestHeaders(),
            $data,
            'post',
            201,
        );

        // Check the response
        $response->assertJsonStructure([
            'data' => [
                'master' => [
                    'id',
                ],
            ],
        ]);

        // Check colli numbers
        $this->assertCount(2, $response->json('data.colli'));
        $this->assertEquals(1, $response->json('data.colli.0.attributes.collo_number'));
        $this->assertEquals(2, $response->json('data.colli.1.attributes.collo_number'));

        // Check descriptions
        $this->assertEquals('246200951946-7852', $response->json('data.master.attributes.description'));
        $this->assertEquals('Shipment item 1', $response->json('data.colli.0.attributes.description'));
        $this->assertEquals('Shipment item 2', $response->json('data.colli.1.attributes.description'));
    }

    public function testGetShipmentService()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');

        $this->bindHttpClientMock();

        // TODO: Add carrier response stub for getting a shipment's service.
        // See the "Response Stubs" chapter in the readme for more info.

        $shipmentId = '';
        $response = $this->getJson("/shipments/$shipmentId/service", $this->getRequestHeaders());

        // Check the response
        // TODO: assert response structure based on transformed carrier response stub
        $response->assertJsonStructure([
            'data' => [
                'code' => '',
                'name' => '',
            ],
        ]);
    }
}
