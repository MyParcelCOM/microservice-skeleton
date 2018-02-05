<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use Mockery;
use MyParcelCom\Common\Resources\PromiseResources;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationRepository;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use PHPUnit\Framework\TestCase;

class PickUpDropOffLocationRepositoryTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function testGetAll()
    {
        $pickUpDropOffLocationRepository = (new PickUpDropOffLocationRepository())
            ->setCarrierApiGateway(new CarrierApiGatewayMock());

        $promise = $pickUpDropOffLocationRepository->getAll('UK', 'EC1A 1BB');

        $this->assertInstanceOf(PromiseResources::class, $promise);
    }
}
