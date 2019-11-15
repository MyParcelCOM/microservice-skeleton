<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use DateTime;
use MyParcelCom\Microservice\PickUpDropOffLocations\OpeningHour;
use PHPUnit\Framework\TestCase;

class OpeningHourTest extends TestCase
{
    /** @var OpeningHour */
    private $openingHour;

    protected function setUp(): void
    {
        parent::setUp();
        $this->openingHour = new OpeningHour();
    }

    /** @test */
    public function testDay(): void
    {
        $this->assertEquals('yesterday', $this->openingHour->setDay('yesterday')->getDay());
    }

    /** @test */
    public function testOpen(): void
    {
        $today = new DateTime();
        $this->assertNull($this->openingHour->getOpen(), '`getOpen()` should return `null` when not set');
        $this->assertEquals($today, $this->openingHour->setOpen($today)->getOpen());
    }

    /** @test */
    public function testClosed(): void
    {
        $today = new DateTime();
        $this->assertNull($this->openingHour->getClosed(), '`getClosed()` should return `null` when not set');
        $this->assertEquals($today, $this->openingHour->setClosed($today)->getClosed());
    }
}
