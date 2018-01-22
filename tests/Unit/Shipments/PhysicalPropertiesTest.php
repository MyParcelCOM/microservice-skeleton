<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use PHPUnit\Framework\TestCase;
use TypeError;

class PhysicalPropertiesTest extends TestCase
{
    /** @test */
    public function testHeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(100, $physicalProperties->setHeight(100)->getHeight());
    }

    /** @test */
    public function testWidth()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(78, $physicalProperties->setWidth(78)->getWidth());
    }

    /** @test */
    public function testLength()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(12, $physicalProperties->setLength(12)->getLength());
    }

    /** @test */
    public function testVolume()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(87.2, $physicalProperties->setVolume(87.2)->getVolume());
    }

    /** @test */
    public function testWeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(9, $physicalProperties->setWeight(9)->getWeight());
    }

    /** @test */
    public function testUninitializedHeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->expectException(TypeError::class);
        $physicalProperties->getHeight();
    }

    /** @test */
    public function testUninitializedWidth()
    {
        $physicalProperties = new PhysicalProperties();
        $this->expectException(TypeError::class);
        $physicalProperties->getWidth();
    }

    /** @test */
    public function testUninitializedLength()
    {
        $physicalProperties = new PhysicalProperties();
        $this->expectException(TypeError::class);
        $physicalProperties->getLength();
    }

    /** @test */
    public function testUninitializedVolume()
    {
        $physicalProperties = new PhysicalProperties();
        $this->expectException(TypeError::class);
        $physicalProperties->getVolume();
    }

    /** @test */
    public function testUninitializedWeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->expectException(TypeError::class);
        $physicalProperties->getWeight();
    }
}
