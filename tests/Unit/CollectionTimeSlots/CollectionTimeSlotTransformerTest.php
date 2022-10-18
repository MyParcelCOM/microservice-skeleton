<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\CollectionTimeSlots;

use Illuminate\Routing\UrlGenerator;
use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\CollectionTimeSlots\CollectionTimeSlot;
use MyParcelCom\Microservice\CollectionTimeSlots\CollectionTimeSlotTransformer;
use MyParcelCom\Microservice\Tests\TestCase;
use stdClass;

class CollectionTimeSlotTransformerTest extends TestCase
{
    private CollectionTimeSlot $collectionTimeSlot;
    private CollectionTimeSlotTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        $this->transformer = new CollectionTimeSlotTransformer($transformerFactory);
        $this->transformer->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));

        $this->collectionTimeSlot = Mockery::mock(CollectionTimeSlot::class, [
            'getId'   => 'time-slot-id',
            'getFrom' => '2022-03-24T09:30:00+01:00',
            'getTo'   => '2022-03-24T10:30:00+01:00',
        ]);
    }

    /** @test */
    public function testItGetsAttributesFromModel()
    {
        $this->assertEquals([
            'from' => '2022-03-24T09:30:00+01:00',
            'to'   => '2022-03-24T10:30:00+01:00',
        ], $this->transformer->getAttributes($this->collectionTimeSlot));
    }

    /** @test */
    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->transformer->getAttributes(new stdClass());
    }
}
