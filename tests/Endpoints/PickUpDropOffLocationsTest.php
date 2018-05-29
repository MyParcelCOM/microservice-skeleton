<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * @group Endpoints:PickUpDropOff
 * @group Implementation
 */
class PickUpDropOffLocationsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

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

    /** @test */
    public function itCanFilterPickUpAndDropOffLocationsByCategories()
    {
        $this->bindCarrierApiGatewayMock();

        // TODO: This method also requires the response stub as mentioned in itRetrievesAndMapsPickUpAndDropOffLocations().

        // Retrieve only pick-up locations.
        $pickupResponse = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BBs?filter[categories]=pick-up',
            $this->getRequestHeaders()
        );
        array_walk($pickupResponse->getContent()->data, function ($pudoPoint) {
            $this->assertNotContains('drop-off', $pudoPoint->attributes->categories);
        });

        // Retrieve only drop-off locations.
        $dropoffResponse = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=drop-off',
            $this->getRequestHeaders()
        );
        array_walk($pickupResponse->getContent()->data, function ($pudoPoint) {
            $this->assertNotContains('pick-up', $pudoPoint->attributes->categories);
        });

        // Retrieve both pick-up and drop-off locations.
        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders()
        );
    }
}
