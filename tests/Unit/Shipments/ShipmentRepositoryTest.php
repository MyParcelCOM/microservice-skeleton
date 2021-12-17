<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentMapper;
use MyParcelCom\Microservice\Shipments\ShipmentRepository;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use PHPUnit\Framework\TestCase;

class ShipmentRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function testCreateFromPostData()
    {
        $postData = json_decode(file_get_contents(base_path('tests/Stubs/shipment-request.json')), true);

        $shipmentRepository = new ShipmentRepository(new CarrierApiGatewayMock(), new ShipmentMapper());

        $shipment = $shipmentRepository->createFromPostData($postData['data'], $postData['meta']);

        $this->assertInstanceOf(Shipment::class, $shipment);
    }
}
