<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\ServiceRates;

use MyParcelCom\Microservice\ServiceRates\Price;
use MyParcelCom\Microservice\ServiceRates\ServiceRate;
use MyParcelCom\Microservice\Tests\TestCase;

class ServiceRateTest extends TestCase {

    private ServiceRate $serviceRate;

    private Price $price;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceRate = new ServiceRate();
        $this->price = new Price(10, 'EUR');
    }

    /** @test */
    public function testItCanBeCreated(): void
    {
        $this->assertInstanceOf(ServiceRate::class, $this->serviceRate);
    }

    /** @test */
    public function testItCanGetAndSetCode(): void
    {
        $this->assertNull($this->serviceRate->getCode(), '`getCode()` should return `null` when not set');
        $this->serviceRate->setCode('test');
        $this->assertEquals('test', $this->serviceRate->getCode());
    }

    /** @test */
    public function testItCanGetAndSetWeightMin(): void
    {
        $this->assertNull($this->serviceRate->getWeightMin(), '`getWeightMin()` should return `null` when not set' );
        $this->serviceRate->setWeightMin(1);
        $this->assertEquals(1, $this->serviceRate->getWeightMin());
    }

    /** @test */
    public function testItCanGetAndSetWeightMax(): void
    {
        $this->assertNull($this->serviceRate->getWeightMax(), '`getWeightMax()` should return `null` when not set' );
        $this->serviceRate->setWeightMax(1);
        $this->assertEquals(1, $this->serviceRate->getWeightMax());
    }

    /** @test */
    public function testItCanGetAndSetLengthMax(): void
    {
        $this->assertNull($this->serviceRate->getLengthMax(), '`getLengthMax()` should return `null` when not set' );
        $this->serviceRate->setLengthMax(1);
        $this->assertEquals(1, $this->serviceRate->getLengthMax());
    }

    /** @test */
    public function testItCanGetAndSetWidthMax(): void
    {
        $this->assertNull($this->serviceRate->getWidthMax(), '`getWidthMax()` should return `null` when not set' );
        $this->serviceRate->setWidthMax(1);
        $this->assertEquals(1, $this->serviceRate->getWidthMax());
    }

    /** @test */
    public function testItCanGetAndSetHeightMax(): void
    {
        $this->assertNull($this->serviceRate->getHeightMax(), '`getHeightMax()` should return `null` when not set' );
        $this->serviceRate->setHeightMax(1);
        $this->assertEquals(1, $this->serviceRate->getHeightMax());
    }

    /** @test */
    public function testItCanGetAndSetVolumeMax(): void
    {
        $this->assertNull($this->serviceRate->getVolumeMax(), '`getVolumeMax()` should return `null` when not set' );
        $this->serviceRate->setVolumeMax(1.19);
        $this->assertEquals(1.19, $this->serviceRate->getVolumeMax());
    }

    /** @test */
    public function testItCanGetAndSetPrice(): void
    {
        $this->serviceRate->setPrice($this->price);
        $this->assertEquals($this->price, $this->serviceRate->getPrice());
    }

    /** @test */
    public function testItCanGetAndSetPurchasePrice(): void
    {
        $this->serviceRate->setPurchasePrice($this->price);
        $this->assertEquals($this->price, $this->serviceRate->getPurchasePrice());
    }

    /** @test */
    public function testItCanGetAndSetFuelSurcharge(): void
    {
        $this->assertNull($this->serviceRate->getFuelSurcharge(), '`getFuelSurcharge()` should return `null` when not set' );
        $this->serviceRate->setFuelSurcharge($this->price);
        $this->assertEquals($this->price, $this->serviceRate->getFuelSurcharge());
    }

}
