<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Error;
use MyParcelCom\Microservice\Shipments\Option;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    /** @test */
    public function testCode()
    {
        $option = new Option();
        $this->assertEquals('code123', $option->setCode('code123')->getCode());
    }

    /** @test */
    public function testUninitializedCode()
    {
        $option = new Option();
        $this->expectException(Error::class);
        $option->getCode();
    }

    /** @test */
    public function testName()
    {
        $option = new Option();
        $this->assertNull($option->getName());
        $this->assertEquals('johan', $option->setName('johan')->getName());
    }
}
