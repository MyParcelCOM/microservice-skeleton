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

    protected function setUp(): void
    {
        parent::setUp();

        $this->bindHttpClientMock();
    }

    /** @test */
    public function itRetrievesAndMapsPickUpAndDropOffLocations()
    {
        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/pickup-dropoff-locations/GB/B694DA',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/GB/B694DA',
            $this->getRequestHeaders(),
        );

        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{latitude}/{longitude}',
            '/pickup-dropoff-locations/52.359686/4.884573',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/52.359686/4.884573',
            $this->getRequestHeaders(),
        );
    }

    /** @test */
    public function getPickUpAndDropOffLocationsFilteredByPickUpCategory()
    {
        // by country and postal code

        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[categories]=pick-up',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[categories]=pick-up',
            $this->getRequestHeaders(),
        );
        $locations = $response->json('data');
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('pick-up', $pudoPoint['attributes']['categories']);
        });

        // by geolocation

        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{latitude}/{longitude}',
            '/pickup-dropoff-locations/52.359686/4.884573?filter[categories]=pick-up',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/52.359686/4.884573?filter[categories]=pick-up',
            $this->getRequestHeaders(),
        );
        $locations = $response->json('data');
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('pick-up', $pudoPoint['attributes']['categories']);
        });
    }

    /** @test */
    public function getPickUpAndDropOffLocationsFilteredByDropOffCategory()
    {
        // by country and postal code

        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[categories]=drop-off',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[categories]=drop-off',
            $this->getRequestHeaders(),
        );
        $locations = $response->json('data');
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('drop-off', $pudoPoint['attributes']['categories']);
        });

        // by geolocation

        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{latitude}/{longitude}',
            '/pickup-dropoff-locations/52.359686/4.884573?filter[categories]=drop-off',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/52.359686/4.884573?filter[categories]=drop-off',
            $this->getRequestHeaders(),
        );
        $locations = $response->json('data');
        array_walk($locations, function ($pudoPoint) {
            $this->assertContains('drop-off', $pudoPoint['attributes']['categories']);
        });
    }

    /** @test */
    public function getPickUpAndDropOffLocationsFilteredByPickUpAndDropOffCategories()
    {
        // by country and postal code

        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders(),
        );
        $locations = $response->json('data');
        array_walk($locations, function ($pudoPoint) {
            $categories = $pudoPoint['attributes']['categories'];
            $this->assertTrue(in_array('drop-off', $categories) || in_array('pick-up', $categories));
        });

        // by geolocation

        $response = $this->assertJsonSchema(
            '/pickup-dropoff-locations/{latitude}/{longitude}',
            '/pickup-dropoff-locations/52.359686/4.884573?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders(),
        );
        $this->assertJsonDataCount(
            2, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/52.359686/4.884573?filter[categories]=pick-up,drop-off',
            $this->getRequestHeaders(),
        );
        $locations = $response->json('data');
        array_walk($locations, function ($pudoPoint) {
            $categories = $pudoPoint['attributes']['categories'];
            $this->assertTrue(in_array('drop-off', $categories) || in_array('pick-up', $categories));
        });
    }

    /** @test */
    public function itRetrievesAndMapsPickUpAndDropOffLocationsFilterByFeature()
    {
        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{latitude}/{longitude}',
            '/pickup-dropoff-locations/52.359686/4.884573?filter[features]=print-label-in-store',
            $this->getRequestHeaders(),
        );

        $this->assertJsonDataCount(
            10, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/52.359686/4.884573?filter[features]=print-label-in-store',
            $this->getRequestHeaders(),
        );

        $this->assertJsonSchema(
            '/pickup-dropoff-locations/{country_code}/{postal_code}',
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[features]=print-label-in-store',
            $this->getRequestHeaders(),
        );

        $this->assertJsonDataCount(
            10, // TODO: Update this according to the used stub.
            '/pickup-dropoff-locations/GB/EC1A 1BB?filter[features]=print-label-in-store',
            $this->getRequestHeaders(),
        );
    }

    /** @test */
    public function itRetrievesAPickUpAndDropOffLocationById()
    {
        $this->markTestSkipped();

        $pudoLocationId = 'pudo-location-id'; // TODO: Update this according to the used stub.
        $this->assertJsonSchema(
            "/pickup-dropoff-locations/{pudo_id}",
            "/pickup-dropoff-locations/$pudoLocationId",
            $this->getRequestHeaders(),
        );

        $this->assertJsonDataCount(
            1,
            "/pickup-dropoff-locations/$pudoLocationId",
            $this->getRequestHeaders(),
        );
    }

    /** @test */
    public function itReturns404IfNoPudoLocationsAreFound()
    {
        $this->markTestSkipped();

        $pudoLocationId = 'non-existent-pudo-location-id';
        $this->get("/pickup-dropoff-locations/$pudoLocationId", $this->getRequestHeaders())
            ->assertNotFound();
    }
}
