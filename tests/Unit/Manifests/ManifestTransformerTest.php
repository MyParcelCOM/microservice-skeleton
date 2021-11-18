<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Manifests;

use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Tests\TestCase;
use stdClass;

class ManifestTransformerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        // todo: declare new transformer instances
        // todo: declare new Manifest
    }

    /** @test */
    public function testItGetsAttributesFromModel()
    {
        // todo: match array with getAttributes function outcome
    }

    /** @test */
    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->transformer->getAttributes(new stdClass());
    }
}
