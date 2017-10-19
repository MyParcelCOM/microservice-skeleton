<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\Service;
use PHPUnit\Framework\TestCase;
use TypeError;

class ServiceTest extends TestCase
{
    /** @test */
    public function testCode()
    {
        $service = new Service();
        $this->assertEquals('code123', $service->setCode('code123')->getCode());
    }

    /** @test */
    public function testUninitializedCode()
    {
        $service = new Service();
        $this->expectException(TypeError::class);
        $service->getCode();
    }

    /** @test */
    public function testName()
    {
        $service = new Service();
        $this->assertNull($service->getName());
        $this->assertEquals('johan', $service->setName('johan')->getName());
    }
}
