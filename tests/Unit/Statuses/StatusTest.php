<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses;

use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use MyParcelCom\Microservice\Statuses\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /** @var Status */
    private $status;

    protected function setUp(): void
    {
        parent::setUp();
        $this->status = new Status();
    }

    /** @test */
    public function testId(): void
    {
        $this->assertNull($this->status->getId(), '`getId()` should return `null` when not set');
        $this->assertEquals('OO7', $this->status->setId('OO7')->getId());
    }

    /** @test */
    public function testCode(): void
    {
        $this->assertNull($this->status->getCode(), '`getCode()` should return `null` when not set');
        $this->assertEquals('great_success', $this->status->setCode('great_success')->getCode());
    }

    /** @test */
    public function testDescription(): void
    {
        $this->assertNull($this->status->getDescription(), '`getDescription()` should return `null` when not set');
        $this->assertEquals('asc', $this->status->setDescription('asc')->getDescription());
    }

    /** @test */
    public function testTimestamp(): void
    {
        $this->assertNull($this->status->getTimestamp(), '`getTimestamp()` should return `null` when not set');
        $this->assertEquals(9000, $this->status->setTimestamp(9000)->getTimestamp());
    }

    /** @test */
    public function testPhysicalProperties()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals($physicalProperties, $this->status->setPhysicalProperties($physicalProperties)->getPhysicalProperties());
    }
}
