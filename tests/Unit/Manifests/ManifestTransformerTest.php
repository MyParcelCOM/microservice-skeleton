<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Manifests;

use Illuminate\Routing\UrlGenerator;
use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Manifests\Manifest;
use MyParcelCom\Microservice\Manifests\ManifestTransformer;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;
use MyParcelCom\Microservice\Tests\TestCase;
use stdClass;

class ManifestTransformerTest extends TestCase
{
    private Manifest $manifest;
    private ManifestTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $addressData = [
            'street_1'     => 'Binnenhof',
            'city'         => 'Den Haag',
            'country_code' => 'NL',
            'company'      => 'Lockdown BV',
        ];

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        $this->transformer = (new ManifestTransformer($transformerFactory))->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));
        $this->manifest = new Manifest(
            'my-test-manifest',
            new AddressJson($addressData),
            new ContactJson($addressData)
        );
    }

    /** @test */
    public function testItGetsAttributesFromModel()
    {
        $this->assertEquals([
            'name'    => 'my-test-manifest',
            'address' => [
                'street_1'     => 'Binnenhof',
                'city'         => 'Den Haag',
                'country_code' => 'NL',
                'company'      => 'Lockdown BV',
            ],
        ], $this->transformer->getAttributes($this->manifest));
    }

    /** @test */
    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->transformer->getAttributes(new stdClass());
    }
}
