<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\PickUpDropOffLocations\OpeningHour;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocation;
use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use PHPUnit\Framework\TestCase;

class PickUpDropOffLocationTest extends TestCase
{
    /** @var PickUpDropOffLocation */
    private $location;

    protected function setUp(): void
    {
        parent::setUp();
        $this->location = new PickUpDropOffLocation();
    }

    /** @test */
    public function testId(): void
    {
        $this->assertNull($this->location->getId(), '`getId()` should return `null` when not set');
        $this->assertEquals('woeid', $this->location->setId('woeid')->getId());
    }

    /** @test */
    public function testCategories()
    {
        $this->assertEquals([], $this->location->getCategories());
        $this->assertEquals(
            ['pick-up'],
            $this->location->setCategories(['pick-up'])->getCategories(),
        );
    }

    /** @test */
    public function testAddress(): void
    {
        $address = (new Address())->setFirstName('Tini')->setLastName('Plini');
        $this->assertNull($this->location->getAddress(), '`getAddress()` should return `null` when not set');
        $this->assertEquals($address, $this->location->setAddress($address)->getAddress());
    }

    /** @test */
    public function testOpeningHours(): void
    {
        $openingHour = (new OpeningHour())->setDay('good day sir');
        $this->assertEmpty($this->location->getOpeningHours(), '`getOpeningHours()` should return `[]` when not set');
        $this->assertEquals([$openingHour], $this->location->setOpeningHours($openingHour)->getOpeningHours());
    }

    /** @test */
    public function testPosition(): void
    {
        $position = (new Position())->setLatitude(52.306085);
        $this->assertNull($this->location->getPosition(), '`getPosition()` should return `null` when not set');
        $this->assertEquals($position, $this->location->setPosition($position)->getPosition());
    }

    /** @test */
    public function testDistance(): void
    {
        $this->assertNull($this->location->getDistance(), '`getDistance()` should return `null` when not set');
        $this->assertEquals(1337, $this->location->setDistance(1337)->getDistance());
    }

    public function testLocationType(): void
    {
        $this->assertNull($this->location->getLocationType(), '`getLocationType()` should return `null` when not set');
        $this->assertEquals('office', $this->location->setLocationType('office')->getLocationType());
    }
}
