<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\ServiceRates;

use Illuminate\Routing\UrlGenerator;
use Mockery;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\ServiceRates\Price;
use MyParcelCom\Microservice\ServiceRates\ServiceRate;
use MyParcelCom\Microservice\ServiceRates\ServiceRateTransformer;
use MyParcelCom\Microservice\Tests\TestCase;
use stdClass;

class ServiceRateTransformerTest extends TestCase
{
    private ServiceRateTransformer $transformer;
    private ServiceRate $serviceRate;

    protected function setUp(): void
    {
        parent::setUp();

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        $this->transformer = (new ServiceRateTransformer($transformerFactory))->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));

        $this->serviceRate = Mockery::mock(ServiceRate::class, [
            'getId'            => null,
            'getCode'          => 'fedex-rate',
            'getWeightMin'     => 1,
            'getWeightMax'     => 2000,
            'getLengthMax'     => 200,
            'getWidthMax'      => 300,
            'getHeightMax'     => 400,
            'getVolumeMax'     => 30,
            'getPrice'         => Mockery::mock(Price::class, ['getAmount' => 100, 'getCurrency' => 'GBP']),
            'getPurchasePrice' => Mockery::mock(Price::class, ['getAmount' => 75, 'getCurrency' => 'GBP']),
            'getFuelSurcharge' => Mockery::mock(Price::class, ['getAmount' => 15, 'getCurrency' => 'GBP']),
        ]);
    }

    /** @test */
    public function testItGetsAttributesFromModel()
    {
        $this->assertEquals([
            'code'           => 'fedex-rate',
            'weight_min'     => 1,
            'weight_max'     => 2000,
            'length_max'     => 200,
            'width_max'      => 300,
            'height_max'     => 400,
            'volume_max'     => 30,
            'price'          => ['amount' => 100, 'currency' => 'GBP'],
            'purchase_price' => ['amount' => 75, 'currency' => 'GBP'],
            'fuel_surcharge' => ['amount' => 15, 'currency' => 'GBP'],
        ], $this->transformer->getAttributes($this->serviceRate));
    }

    /** @test */
    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->transformer->getAttributes(new stdClass());
    }
}
