<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Common\Traits\JsonApiAssertionsTrait;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

/**
 * @group Endpoints:PickUpDropOff
 */
class PickUpDropOffLocationsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    /** @test */
    public function itRetrievesAndMapsPickUpAndDropOffLocations()
    {
        $this->bindCarrierApiGatewayMock();

        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB',
            $this->getRequestHeaders()
        );
    }
}
