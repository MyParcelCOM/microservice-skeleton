<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use Mockery;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Geo\GeoService;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationRepository;
use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class PickUpDropOffLocationRepositoryTest extends TestCase
{
    /** @var PickUpDropOffLocationRepository */
    private $pickUpDropOffLocationRepository;

    protected function setUp()
    {
        parent::setUp();

        $cache = Mockery::mock(CacheInterface::class, ['get' => null, 'set' => true]);

        $geoService = Mockery::mock(GeoService::class, [
            'getPositionForAddress' => Mockery::mock(Position::class, [
                'getLatitude'  => 52.304860,
                'getLongitude' => 4.691103,
                'getDistance'  => null,
            ]),
            'getDistance'           => 137,
        ]);

        $this->pickUpDropOffLocationRepository = (new PickUpDropOffLocationRepository())
            ->setCache($cache)
            ->setGeoService($geoService)
            ->setCarrierApiGateway(new CarrierApiGatewayMock());
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * @test
     * @group Implementation
     */
    public function testGetAll()
    {
        $resources = $this->pickUpDropOffLocationRepository->getAll('UK', 'EC1A 1BB');

        $this->assertInstanceOf(ResourcesInterface::class, $resources);
    }
}
