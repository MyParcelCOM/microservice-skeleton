<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    /** @var Position */
    private $position;

    protected function setUp(): void
    {
        parent::setUp();
        $this->position = new Position();
    }

    /** @test */
    public function testLatitude(): void
    {
        $this->assertNull($this->position->getLatitude(), '`getLatitude()` should return `null` when not set');
        $this->assertEquals(13.37, $this->position->setLatitude(13.37)->getLatitude());
    }

    /** @test */
    public function testLongitude(): void
    {
        $this->assertNull($this->position->getLongitude(), '`getLongitude()` should return `null` when not set');
        $this->assertEquals(13.37, $this->position->setLongitude(13.37)->getLongitude());
    }
}
