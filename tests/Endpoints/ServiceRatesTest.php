<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * @group Endpoints:ServiceRate
 * @group Implementation
 */
class ServiceRatesTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    /** @test */
    public function testGetServiceRates()
    {
        $this->bindHttpClientMock();

        // TODO: Add carrier response stub for requesting a status.
        // See the "Response Stubs" chapter in the readme for more info.

        $this->assertJsonSchema(
            '/get-service-rates',
            '/get-service-rates',
            $this->getRequestHeaders()
        );
    }
}
