<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit;

use Mockery;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Http\Middleware\ExtractCredentials;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

class ExtractCredentialsTest extends TestCase
{
    use CommunicatesWithCarrier;

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function testHandle()
    {
        $gateway = Mockery::mock(CarrierApiGatewayInterface::class)
            ->shouldReceive('setCredentials')
            ->once()
            ->with(\GuzzleHttp\json_encode($this->getApiCredentials()))
            ->andReturnSelf()
            ->getMock();

        $middleware = new ExtractCredentials($gateway);

        $request = Mockery::mock(Request::class)
            ->shouldReceive('header')
            ->once()
            ->with('X-MYPARCELCOM-CREDENTIALS')
            ->andReturn(\GuzzleHttp\json_encode($this->getApiCredentials()))
            ->getMock();

        $middleware->handle($request, function ($nextRequest) use ($request) {
            $this->assertEquals($request, $nextRequest);
        });
    }
}
