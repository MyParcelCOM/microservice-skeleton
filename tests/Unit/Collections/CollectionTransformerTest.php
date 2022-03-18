<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Collections;

use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;
use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Collections\Collection;
use MyParcelCom\Microservice\Collections\CollectionTransformer;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;
use MyParcelCom\Microservice\Shipments\File;
use MyParcelCom\Microservice\Tests\TestCase;
use stdClass;

class CollectionTransformerTest extends TestCase
{
    private Collection $collection;
    private CollectionTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        $this->transformer = new CollectionTransformer($transformerFactory);
        $this->transformer->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));

        Carbon::setTestNow(Carbon::now());

        $this->collection = new Collection([
            'myparcelcom_collection_id' => '6e287731-c391-4548-bc48-c09327e1e94f',
            'name'                      => 'First collection',
            'collection_date'           => '2021-12-24',
            'collection_time_from'      => '12:00',
            'collection_time_to'        => '22:00',
            'address_json'              => new AddressJson([
                'street_1'             => 'Baker Street',
                'street_2'             => 'Marylebone',
                'street_number'        => 221,
                'street_number_suffix' => 'B',
                'postal_code'          => 'NW1 6XE',
                'city'                 => 'London',
                'state_code'           => 'ENG',
                'country_code'         => 'GB',
            ]),
            'contact_json'              => new ContactJson([
                'first_name'   => 'Sherlock',
                'last_name'    => 'Holmes',
                'company'      => 'Holmes Investigations',
                'email'        => 's.holmes@holmesinvestigations.com',
                'phone_number' => '+31 234 567 890',
            ]),
            'tracking_code'             => 'Track-my-collection',
            'registered_at'             => Carbon::now(),
        ]);
        $this->collection->setFiles(
            collect([
                Mockery::mock(File::class, [
                    'getType'      => 'manifest',
                    'getMimeType'  => 'application/pdf',
                    'getExtension' => 'pdf',
                    'getData'      => 'some-base-64-data',
                ]),
            ])
        );
    }

    /** @test */
    public function testItGetsAttributesFromModel()
    {
        $this->assertEquals([
            'name'                      => 'First collection',
            'myparcelcom_collection_id' => '6e287731-c391-4548-bc48-c09327e1e94f',
            'collection_date'           => '2021-12-24',
            'collection_time'           => [
                'from' => '12:00',
                'to'   => '22:00',
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
            'registered_at'             => Carbon::now()->getTimestamp(),
            'files'                     => [
                [
                    'resource_type' => 'manifest',
                    'mime_type'     => 'application/pdf',
                    'extension'     => 'pdf',
                    'data'          => 'some-base-64-data',
                ],
            ],
        ], $this->transformer->getAttributes($this->collection));
    }

    /** @test */
    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->transformer->getAttributes(new stdClass());
    }
}
