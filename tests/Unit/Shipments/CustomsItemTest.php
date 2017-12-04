<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\CustomsItem;
use PHPUnit\Framework\TestCase;

class CustomsItemTest extends TestCase
{
    /** @test */
    public function testDescription()
    {
        $item = new CustomsItem();
        $this->assertEquals('Sam Description', $item->setDescription('Sam Description')->getDescription());
    }

    /** @test */
    public function testHsCode()
    {
        $item = new CustomsItem();
        $this->assertEquals('3215.32.54', $item->setHsCode('3215.32.54')->getHsCode());
    }

    /** @test */
    public function testItemValueAmount()
    {
        $item = new CustomsItem();
        $this->assertEquals(1337, $item->setItemValueAmount(1337)->getItemValueAmount());
    }

    /** @test */
    public function testItemValueCurrency()
    {
        $item = new CustomsItem();
        $this->assertEquals('USD', $item->setItemValueCurrency('USD')->getItemValueCurrency());
    }

    /** @test */
    public function testOriginCountryCode()
    {
        $item = new CustomsItem();
        $this->assertEquals('CN', $item->setOriginCountryCode('CN')->getOriginCountryCode());
    }

    /** @test */
    public function testQuantity()
    {
        $item = new CustomsItem();
        $this->assertEquals(12, $item->setQuantity(12)->getQuantity());
    }

    /** @test */
    public function testSku()
    {
        $item = new CustomsItem();
        $this->assertEquals('DVC-2314/12', $item->setSku('DVC-2314/12')->getSku());
    }
}
