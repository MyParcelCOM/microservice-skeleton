<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses;

use MyParcelCom\Microservice\Statuses\Status;
use MyParcelCom\Microservice\Tests\TestCase;

class StatusTest extends TestCase
{
    /** @var Status */
    private $status;

    public function setUp(): void
    {
        parent::setUp();
        $this->status = new Status();
    }

    /** @test */
    public function testId(): void
    {
        $this->assertNull($this->status->getId(), '`getId()` should return `null` when not set');
        $this->assertEquals('OO7', $this->status->setId('OO7')->getId(), '`getId()` did not return set value');
    }

    /** @test */
    public function testCode(): void
    {
        $this->assertNull($this->status->getCode(), '`getCode()` should return `null` when not set');
        $this->assertEquals('great_success', $this->status->setCode('great_success')->getCode(), '`getCode()` did not return set value');
    }

    /** @test */
    public function testDescription(): void
    {
        $this->assertNull($this->status->getDescription(), '`getDescription()` should return `null` when not set');
        $this->assertEquals('asc', $this->status->setDescription('asc')->getDescription(), '`getDescription()` did not return set value');
    }

    /** @test */
    public function testTimestamp(): void
    {
        $this->assertNull($this->status->getTimestamp(), '`getTimestamp()` should return `null` when not set');
        $this->assertEquals(9000, $this->status->setTimestamp(9000)->getTimestamp(), '`getTimestamp()` did not return set value');
    }
}
