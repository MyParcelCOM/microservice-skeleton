<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use Mockery;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationRepository;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class PickUpDropOffLocationRepositoryTest extends TestCase
{
    /** @var PickUpDropOffLocationRepository */
    private $pickUpDropOffLocationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $cache = Mockery::mock(CacheInterface::class, ['get' => null, 'set' => true]);

        $this->pickUpDropOffLocationRepository = (new PickUpDropOffLocationRepository())
            ->setCache($cache)
            ->setCarrierApiGateway(new CarrierApiGatewayMock());
    }

    protected function tearDown(): void
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
