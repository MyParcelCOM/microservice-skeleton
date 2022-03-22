<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Collections;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use MyParcelCom\Microservice\Collections\Collection;
use MyParcelCom\Microservice\Collections\CollectionMapper;
use MyParcelCom\Microservice\Tests\TestCase;

class CollectionMapperTest extends TestCase
{
    private array $collectionData = [
        'type'       => 'collections',
        'attributes' => [
            'myparcelcom_collection_id' => '6e287731-c391-4548-bc48-c09327e1e94f',
            'name'                      => 'First collection',
            'collection_time'           => [
                'from' => 14123123123,
                'to'   => '2022-03-22T00:00:00+00:00',
            ],
            'address'                   => [
                'street_1'             => 'Baker Street',
                'street_2'             => 'Marylebone',
                'street_number'        => 221,
                'street_number_suffix' => 'B',
                'postal_code'          => 'NW1 6XE',
                'city'                 => 'London',
                'state_code'           => 'ENG',
                'country_code'         => 'GB',
                'first_name'           => 'Sherlock',
                'last_name'            => 'Holmes',
                'company'              => 'Holmes Investigations',
                'email'                => 's.holmes@holmesinvestigations.com',
                'phone_number'         => '+31 234 567 890',
            ],
            'tracking_code'             => 'Track-my-collection',
        ],
    ];

    /** @test */
    public function testItMapsCollectionDataToACollectionModel()
    {
        $mapper = new CollectionMapper();
        $collection = $mapper->map($this->collectionData, new Collection());

        $this->assertEquals(
            $collection->getMyparcelcomCollectionId(),
            Arr::get($this->collectionData, 'attributes.myparcelcom_collection_id')
        );
        $this->assertEquals(
            $collection->getName(),
            Arr::get($this->collectionData, 'attributes.name')
        );
        $this->assertEquals(
            $collection->getCollectionTimeFrom()->timestamp,
            (new Carbon(Arr::get($this->collectionData, 'attributes.collection_time.from')))->timestamp
        );
        $this->assertEquals(
            $collection->getCollectionTimeTo()->timestamp,
            (new Carbon(Arr::get($this->collectionData, 'attributes.collection_time.to')))->timestamp
        );
        $this->assertEquals(
            $collection->getAddressJson()->toArrayWith($collection->getContactJson()),
            Arr::get($this->collectionData, 'attributes.address')
        );
        $this->assertEquals(
            $collection->getTrackingCode(),
            Arr::get($this->collectionData, 'attributes.tracking_code')
        );
    }

    /** @test */
    public function testNotPassingAModelWillCreateANewOne()
    {
        $mapper = new CollectionMapper();

        $this->assertInstanceOf(Collection::class, $mapper->map($this->collectionData));
    }
}
