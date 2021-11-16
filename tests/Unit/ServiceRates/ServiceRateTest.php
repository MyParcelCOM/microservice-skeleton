<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\ServiceRates;

use MyParcelCom\Microservice\ServiceRates\Price;
use MyParcelCom\Microservice\ServiceRates\ServiceRate;
use MyParcelCom\Microservice\Tests\TestCase;

class ServiceRateTest extends TestCase
{
    private ServiceRate $serviceRate;
    private Price $price;

    protected function setUp(): void
    {
        parent::setUp();

        $this->price = new Price(10, 'EUR');
        $this->serviceRate = new ServiceRate($this->price, $this->price);
    }

    /** @test */
    public function testItCanBeCreated(): void
    {
        $this->assertInstanceOf(ServiceRate::class, $this->serviceRate);
    }

    /** @test */
    public function testItCanGetAndSetCode(): void
    {
        $this->serviceRate->setCode('test');
        $this->assertEquals('test', $this->serviceRate->getCode());
    }

    /** @test */
    public function testItCanGetAndSetWeightMin(): void
    {
        $this->serviceRate->setWeightMin(1);
        $this->assertEquals(1, $this->serviceRate->getWeightMin());
    }

    /** @test */
    public function testItCanGetAndSetWeightMax(): void
    {
        $this->serviceRate->setWeightMax(1);
        $this->assertEquals(1, $this->serviceRate->getWeightMax());
    }

    /** @test */
    public function testItCanGetAndSetLengthMax(): void
    {
        $this->serviceRate->setLengthMax(1);
        $this->assertEquals(1, $this->serviceRate->getLengthMax());
    }

    /** @test */
    public function testItCanGetAndSetWidthMax(): void
    {
        $this->serviceRate->setWidthMax(1);
        $this->assertEquals(1, $this->serviceRate->getWidthMax());
    }

    /** @test */
    public function testItCanGetAndSetHeightMax(): void
    {
        $this->serviceRate->setHeightMax(1);
        $this->assertEquals(1, $this->serviceRate->getHeightMax());
    }

    /** @test */
    public function testItCanGetAndSetVolumeMax(): void
    {
        $this->serviceRate->setVolumeMax(1.19);
        $this->assertEquals(1.19, $this->serviceRate->getVolumeMax());
    }

    /** @test */
    public function testItCanGetPrice(): void
    {
        $this->assertEquals($this->price, $this->serviceRate->getPrice());
    }

    /** @test */
    public function testItCanGetPurchasePrice(): void
    {
        $this->assertEquals($this->price, $this->serviceRate->getPurchasePrice());
    }

    /** @test */
    public function testItCanGetAndSetFuelSurcharge(): void
    {
        $this->serviceRate->setFuelSurcharge($this->price);
        $this->assertEquals($this->price, $this->serviceRate->getFuelSurcharge());
    }
}
