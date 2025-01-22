<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * @group Endpoints:Status
 * @group Implementation
 */
class StatusesTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    /** @test */
    public function testGetStatuses()
    {
        $this->bindHttpClientMock();

        // TODO: Add carrier response stub for requesting a status.
        // See the "Response Stubs" chapter in the readme for more info.

        $this->assertJsonSchema(
            '/shipments/{shipment_id}/statuses/{tracking_code}',
            '/shipments/0/statuses/3SMKPL6192376',
            $this->getRequestHeaders(),
        );
    }
}
