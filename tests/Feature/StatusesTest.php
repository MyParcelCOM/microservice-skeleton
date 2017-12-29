<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use MyParcelCom\Common\Traits\JsonApiAssertionsTrait;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

class StatusesTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    /** @test */
    public function testGetStatuses()
    {
        $this->bindCarrierApiGatewayMock();

        $this->assertJsonSchema(
            '/shipments/{shipment_id}/statuses/{tracking_code}',
            '/v1/shipments/0/statuses/3SMKPL6192376',
            $this->getRequestHeaders()
        );
    }
}
