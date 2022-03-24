<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Collections;

use Mockery;
use MyParcelCom\JsonApi\Exceptions\ResourceNotFoundException;
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

    // TODO:    Write a test that asserts that the CollectionRepository retrieves a collection from the DB,
    //          updates it, and then saves it to the DB.

    // TODO:    Write a test that asserts that the CollectionRepository will use the CarrierApiGateway to
    //          register the collection with the carrier API.

    /** @test */
    public function testItThrowsAResourceNotFoundExceptionIfACollectionIsNotFoundForThePassedCollectionId()
    {
        // TODO: Unskip this test if the microservice supports collections.
        $this->markTestSkipped('This test is skipped because this microservice does not yet support collections.');

        $data = [
            'type'       => 'collections',
            'id'         => 'collection-id',
            'attributes' => [
                'name' => 'new name',
            ],
        ];

        $collection = Mockery::mock(Collection::class, [
            'save' => true,
        ]);

        $mapper = Mockery::mock(CollectionMapper::class, [
            'map' => $collection,
        ]);

        $repository = new CollectionRepository(new CarrierApiGatewayMock(), $mapper);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('collections');

        $repository->updateFromPatchData('not-a-collection-id', $data);
    }
}
