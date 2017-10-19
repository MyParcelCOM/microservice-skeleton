<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\Option;
use PHPUnit\Framework\TestCase;
use TypeError;

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
        $this->expectException(TypeError::class);
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
