<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Common\Traits\JsonApiAssertionsTrait;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

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
        $this->bindCarrierApiGatewayMock();

        // TODO: Add carrier response stub for requesting a status.
        // See the "Response Stubs" chapter in the readme for more info.

        $this->assertJsonSchema(
            '/shipments/{shipment_id}/statuses/{tracking_code}',
            '/v1/shipments/0/statuses/3SMKPL6192376',
            $this->getRequestHeaders()
        );
    }
}
