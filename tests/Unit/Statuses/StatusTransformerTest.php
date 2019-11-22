<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses;

use Illuminate\Routing\UrlGenerator;
use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Statuses\Status;
use MyParcelCom\Microservice\Statuses\StatusTransformer;
use PHPUnit\Framework\TestCase;
use stdClass;

class StatusTransformerTest extends TestCase
{
    /** @var StatusTransformer */
    private $statusTransformer;

    /** @var Status */
    private $status;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var TransformerFactory $transformerFactory */
        $transformerFactory = Mockery::mock(TransformerFactory::class);

        $this->statusTransformer = (new StatusTransformer($transformerFactory))
            ->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));
        $this->status = Mockery::mock(Status::class, [
            'getId'          => 'w',
            'getCode'        => 'u',
            'getDescription' => 'b',
            'getTimestamp'   => 888,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGetId()
    {
        $this->assertEquals(
            'w',
            $this->statusTransformer->getId($this->status),
            'Failed getting model id.'
        );
    }

    public function testGetAttributes()
    {
        $this->assertEquals([
            'myparcelcom_code' => 'u',
            'description'      => 'b',
            'timestamp'        => 888,
        ], $this->statusTransformer->getAttributes($this->status));
    }

    public function testGetIdWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->statusTransformer->getId(new stdClass());
    }

    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->statusTransformer->getAttributes(new stdClass());
    }
}
