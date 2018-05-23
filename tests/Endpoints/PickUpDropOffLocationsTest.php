<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\JsonApi\Traits\AssertionsTrait;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

/**
 * @group Endpoints:PickUpDropOff
 * @group Implementation
 */
class PickUpDropOffLocationsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use AssertionsTrait;

    /** @test */
    public function itRetrievesAndMapsPickUpAndDropOffLocations()
    {
        $this->bindCarrierApiGatewayMock();

        // TODO: Add carrier response stub for pudo points.
        // See the "Response Stubs" chapter in the readme for more info.

        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB',
            $this->getRequestHeaders()
        );
    }
}
