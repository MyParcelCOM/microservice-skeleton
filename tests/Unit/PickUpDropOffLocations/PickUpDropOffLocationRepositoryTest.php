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

        $promise = $pickUpDropOffLocationRepository->getAll('NL', '2131BC');

        $this->assertInstanceOf(PromiseResources::class, $promise);
        $this->assertEquals(2, $promise->count()); // TODO change count to expected amount
    }
}
