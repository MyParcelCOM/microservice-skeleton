<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Collections;

use Mockery;
use MyParcelCom\Microservice\Collections\Collection;
use MyParcelCom\Microservice\Collections\CollectionMapper;
use MyParcelCom\Microservice\Collections\CollectionRepository;
use MyParcelCom\Microservice\Tests\Mocks\CarrierApiGatewayMock;
use MyParcelCom\Microservice\Tests\TestCase;

class CollectionRepositoryTest extends TestCase
{
    /** @test */
    public function testItSavesACollection()
    {
        $data = [
            'key' => 'value',
            'foo' => [
                'bar' => 'baz',
            ],
        ];

        $collectionMock = Mockery::mock(Collection::class);
        $collectionMock
            ->shouldReceive('save')
            ->once()
            ->andReturnTrue();

        $mapperMock = Mockery::mock(CollectionMapper::class);
        $mapperMock
            ->shouldReceive('map')
            ->once()
            ->with($data)
            ->andReturn($collectionMock);

        $repository = new CollectionRepository(new CarrierApiGatewayMock(), $mapperMock);

        $collection = $repository->createFromPostData($data);
        $this->assertInstanceOf(Collection::class, $collection);
    }
}
