<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /** @var Address */
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->address = new Address();
    }

    /** @test */
    public function testStreet1(): void
    {
        $this->assertNull($this->address->getStreet1(), '`getStreet1()` should return `null` when not set');
        $this->assertEquals('some street', $this->address->setStreet1('some street')->getStreet1(), '`getStreet1()` did not return set value');
    }

    /** @test */
    public function testStreet2(): void
    {
        $this->assertNull($this->address->getStreet2(), '`getStreet2()` should return `null` when not set');
        $this->assertEquals('some street', $this->address->setStreet2('some street')->getStreet2(), '`getStreet2()` did not return set value');
    }

    /** @test */
    public function testStreetNumber(): void
    {
        $this->assertNull($this->address->getStreetNumber(), '`getStreetNumber()` should return `null` when not set');
        $this->assertEquals(1337, $this->address->setStreetNumber(1337)->getStreetNumber(), '`getStreetNumber()` did not return set value');
    }

    /** @test */
    public function testStreetNumberSuffix(): void
    {
        $this->assertNull($this->address->getStreetNumberSuffix(), '`getStreetNumberSuffix()` should return `null` when not set');
        $this->assertEquals('Overkant', $this->address->setStreetNumberSuffix('Overkant')->getStreetNumberSuffix(), '`getStreetNumberSuffix()` did not return set value');
    }

    /** @test */
    public function testPostalCode(): void
    {
        $this->assertNull($this->address->getPostalCode(), '`getPostalCode()` should return `null` when not set');
        $this->assertEquals('9001DBZ', $this->address->setPostalCode('9001DBZ')->getPostalCode(), '`getPostalCode()` did not return set value');
    }

    /** @test */
    public function testCity(): void
    {
        $this->assertNull($this->address->getCity(), '`getCity()` should return `null` when not set');
        $this->assertEquals('Felicity', $this->address->setCity('Felicity')->getCity(), '`getCity()` did not return set value');
    }

    /** @test */
    public function testRegionCode(): void
    {
        $this->assertNull($this->address->getRegionCode(), '`getRegionCode()` should return `null` when not set');
        $this->assertEquals('NH', $this->address->setRegionCode('NH')->getRegionCode(), '`getRegionCode()` did not return set value');
    }

    /** @test */
    public function testCountryCode(): void
    {
        $this->assertNull($this->address->getCountryCode(), '`getCountryCode()` should return `null` when not set');
        $this->assertEquals('NL', $this->address->setCountryCode('NL')->getCountryCode(), '`getCountryCode()` did not return set value');
    }

    /** @test */
    public function testFirstName(): void
    {
        $this->assertNull($this->address->getFirstName(), '`getFirstName()` should return `null` when not set');
        $this->assertEquals('Piet', $this->address->setFirstName('Piet')->getFirstName(), '`getFirstName()` did not return set value');
    }

    /** @test */
    public function testLastName(): void
    {
        $this->assertNull($this->address->getLastName(), '`getLastName()` should return `null` when not set');
        $this->assertEquals('Jansen', $this->address->setLastName('Jansen')->getLastName(), '`getLastName()` did not return set value');
    }

    /** @test */
    public function testCompany(): void
    {
        $this->assertNull($this->address->getCompany(), '`getCompany()` should return `null` when not set');
        $this->assertEquals('ACME', $this->address->setCompany('ACME')->getCompany(), '`getCompany()` did not return set value');
    }

    /** @test */
    public function testEmail(): void
    {
        $this->assertNull($this->address->getEmail(), '`getEmail()` should return `null` when not set');
        $this->assertEquals('piet@acme.nl', $this->address->setEmail('piet@acme.nl')->getEmail(), '`getEmail()` did not return set value');
    }

    /** @test */
    public function testPhoneNumber(): void
    {
        $this->assertNull($this->address->getPhoneNumber(), '`getPhoneNumber()` should return `null` when not set');
        $this->assertEquals('+31 1337 9001', $this->address->setPhoneNumber('+31 1337 9001')->getPhoneNumber(), '`getPhoneNumber()` did not return set value');
    }
}
