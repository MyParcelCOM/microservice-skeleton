<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\ShipmentItem;
use PHPUnit\Framework\TestCase;

class ShipmentItemTest extends TestCase
{
    /** @test */
    public function testDescription()
    {
        $item = new ShipmentItem();
        $this->assertEquals('Sam Description', $item->setDescription('Sam Description')->getDescription());
    }

    /** @test */
    public function testImageUrl()
    {
        $item = new ShipmentItem();
        $this->assertEquals('//get.some', $item->setImageUrl('//get.some')->getImageUrl());
    }

    /** @test */
    public function testHsCode()
    {
        $item = new ShipmentItem();
        $this->assertNull($item->getHsCode());
        $this->assertEquals('3215.32.54', $item->setHsCode('3215.32.54')->getHsCode());
    }

    /** @test */
    public function testItemValueAmount()
    {
        $item = new ShipmentItem();
        $this->assertEquals(1337, $item->setItemValueAmount(1337)->getItemValueAmount());
    }

    /** @test */
    public function testItemValueCurrency()
    {
        $item = new ShipmentItem();
        $this->assertEquals('USD', $item->setItemValueCurrency('USD')->getItemValueCurrency());
    }

    /** @test */
    public function testOriginCountryCode()
    {
        $item = new ShipmentItem();
        $this->assertNull($item->getOriginCountryCode());
        $this->assertEquals('CN', $item->setOriginCountryCode('CN')->getOriginCountryCode());
    }

    /** @test */
    public function testQuantity()
    {
        $item = new ShipmentItem();
        $this->assertEquals(12, $item->setQuantity(12)->getQuantity());
    }

    /** @test */
    public function testSku()
    {
        $item = new ShipmentItem();
        $this->assertNull($item->getSku());
        $this->assertEquals('DVC-2314/12', $item->setSku('DVC-2314/12')->getSku());
    }

    /** @test */
    public function testItemWeight()
    {
        $item = new ShipmentItem();
        $this->assertNull($item->getItemWeight());
        $this->assertEquals(37, $item->setItemWeight(37)->getItemWeight());
    }

    /** @test */
    public function testTax()
    {
        $item = new ShipmentItem();

        $this->assertNull($item->getTaxAmount());
        $this->assertNull($item->getTaxCurrency());

        $item->setTaxAmount(8008);
        $item->setTaxCurrency('EUR');

        $this->assertEquals(8008, $item->getTaxAmount());
        $this->assertEquals('EUR', $item->getTaxCurrency());
    }

    /** @test */
    public function testDuty()
    {
        $item = new ShipmentItem();

        $this->assertNull($item->getDutyAmount());
        $this->assertNull($item->getDutyCurrency());

        $item->setDutyAmount(707);
        $item->setDutyCurrency('USD');

        $this->assertEquals(707, $item->getDutyAmount());
        $this->assertEquals('USD', $item->getDutyCurrency());
    }


    /** @test */
    public function testVatPercentage()
    {
        $item = new ShipmentItem();

        $this->assertNull($item->getVatPercentage());

        $item->setVatPercentage(30);

        $this->assertEquals(30, $item->getVatPercentage());
    }
}
