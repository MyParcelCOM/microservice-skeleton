<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Manifests;

use Illuminate\Routing\UrlGenerator;
use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Manifests\Manifest;
use MyParcelCom\Microservice\Manifests\ManifestTransformer;
use MyParcelCom\Microservice\Tests\TestCase;
use stdClass;

class ManifestTransformerTest extends TestCase
{

    private Manifest $manifest;
    private ManifestTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        $this->transformer = (new ManifestTransformer($transformerFactory))->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));
        $this->manifest = new Manifest('my-test-manifest');
    }

    /** @test */
    public function testItGetsAttributesFromModel()
    {
        $this->assertEquals([
            'name' => 'my-test-manifest',
        ], $this->transformer->getAttributes($this->manifest));
    }

    /** @test */
    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->transformer->getAttributes(new stdClass());
    }
}
