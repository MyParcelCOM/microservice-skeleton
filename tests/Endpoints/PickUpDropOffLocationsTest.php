<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * TODO: Add carrier response stub for pudo points.
 * See the "Response Stubs" chapter in the readme for more info.
 *
 * @group Endpoints:PickUpDropOff
 * @group Implementation
 */
class PickUpDropOffLocationsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->bindCarrierApiGatewayMock();
    }

    /** @test */
    public function itRetrievesAndMapsPickUpAndDropOffLocations()
    {
        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/GB/B694DA',
            $this->getRequestHeaders()
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/v1/pickup-dropoff-locations/GB/B694DA',
            $this->getRequestHeaders()
        );
    }

    /** @test */
    public function getPickUpAndDropOffLocationsFilteredByPickUpCategory()
    {
        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=pick-up',
            $this->getRequestHeaders()
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=pick-up',
            $this->getRequestHeaders()
        );
        $locations = json_decode($response->getContent())->data;
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('pick-up', $pudoPoint->attributes->categories);
        });
    }

    /** @test */
    public function getPickUpAndDropOffLocationsFilteredByDropOffCategory()
    {
        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=drop-off',
            $this->getRequestHeaders()
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=drop-off',
            $this->getRequestHeaders()
        );
        $locations = json_decode($response->getContent())->data;
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('drop-off', $pudoPoint->attributes->categories);
        });
    }

    /** @test */
    public function getPickUpAndDropOffLocationsFilteredByPickUpAndDropOffCategories()
    {
        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders()
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/v1/pickup-dropoff-locations/UK/EC1A 1BB?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders()
        );
        $locations = json_decode($response->getContent())->data;
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('drop-off', $pudoPoint->attributes->categories);
            $this->assertContains('pick-up', $pudoPoint->attributes->categories);
        });
    }
}
