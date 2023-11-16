<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocation;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationRepository;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @group Implementation
 */
class PickUpDropOffLocationRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var PickUpDropOffLocationRepository */
    private $pickUpDropOffLocationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $cache = Mockery::mock(CacheInterface::class, ['get' => null, 'set' => true]);

        $this->pickUpDropOffLocationRepository = new PickUpDropOffLocationRepository(
            new CarrierApiGatewayMock(),
            $cache
        );
    }

    /** @test */
    public function testGetAll()
    {
        $resources = $this->pickUpDropOffLocationRepository->getAllByCountryAndPostalCode('GB', 'EC1A 1BB');

        $this->assertInstanceOf(ResourcesInterface::class, $resources);
    }

    /** @test */
    public function testGetByIdReturnsModel()
    {
        $this->markTestSkipped();

        $pudoLocation = $this->pickUpDropOffLocationRepository->getById('pudo-location-id');

        $this->assertInstanceOf(PickUpDropOffLocation::class, $pudoLocation);
    }

    /** @test */
    public function testGetByIdReturnsNull()
    {
        $pudoLocation = $this->pickUpDropOffLocationRepository->getById('non-existent-pudo-location-id');

        $this->assertNull($pudoLocation);
    }
}
