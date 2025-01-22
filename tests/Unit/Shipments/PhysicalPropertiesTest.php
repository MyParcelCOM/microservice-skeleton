<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use PHPUnit\Framework\TestCase;

class PhysicalPropertiesTest extends TestCase
{
    /** @test */
    public function testHeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertNull($physicalProperties->getHeight());
        $this->assertEquals(100, $physicalProperties->setHeight(100)->getHeight());
    }

    /** @test */
    public function testWidth()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertNull($physicalProperties->getWidth());
        $this->assertEquals(78, $physicalProperties->setWidth(78)->getWidth());
    }

    /** @test */
    public function testLength()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertNull($physicalProperties->getLength());
        $this->assertEquals(12, $physicalProperties->setLength(12)->getLength());
    }

    /** @test */
    public function testVolume()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertNull($physicalProperties->getVolume());
        $this->assertEquals(87.2, $physicalProperties->setVolume(87.2)->getVolume());
    }

    /** @test */
    public function testWeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(9, $physicalProperties->setWeight(9)->getWeight());
    }

    /** @test */
    public function testVolumetricWeight()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals(17, $physicalProperties->setVolumetricWeight(17)->getVolumetricWeight());
    }
}
