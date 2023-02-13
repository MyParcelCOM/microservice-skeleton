<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;
use Ramsey\Uuid\Uuid;

/**
 * @group Endpoints:Collection
 * @group Implementation
 */
class CollectionsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    // TODO: Uncomment this when implementing this.
    // use RefreshDatabase;

    /** @test */
    public function testItSavesACollectionInTheDatabase()
    {
        $this->markTestSkipped('This test should be implemented in microservices for carriers that offer collections, not in the skeleton.');

        $uuid = Uuid::uuid4();
        Carbon::setTestNow(Carbon::now());

        $postData = [
            'data' => [
                'type'       => 'collections',
                'attributes' => [
                    'myparcelcom_collection_id' => $uuid,
                    'name'                      => 'First Collection',
                    'collection_time'           => [
                        'from' => Carbon::now()->getTimestamp(),
                        'to'   => Carbon::now()->addHours(10)->getTimestamp(),
                    ],
                    'address'                   => [
                        'street_1'             => 'My Street',
                        'street_2'             => 'Third Floor',
                        'street_number'        => 55,
                        'street_number_suffix' => 'A',
                        'postal_code'          => '1111AA',
                        'city'                 => 'Amsterdam',
                        'state_code'           => 'NH',
                        'country_code'         => 'NL',
                        'first_name'           => 'Test',
                        'last_name'            => 'Tester',
                        'company'              => 'Acme Co.',
                        'email'                => 'user@example.com',
                        'phone_number'         => '12312312323',
                    ],
                    'created_at'                => 1647253015,
                ],
            ],
        ];

        $response = $this->assertJsonSchema(
            '/collections',
            '/collections',
            $this->getRequestHeaders(),
            $postData,
            'post',
            201
        );

        $this->assertDatabaseHas('collections', [
            'id'                                 => $response->json('data.id'),
            'myparcelcom_colletion_id'           => $uuid,
            'name'                               => 'First Collection',
            'collection_time_from'               => Carbon::now(),
            'collection_time_to'                 => Carbon::now()->addHours(10),
            'address_json->street_1'             => 'My Street',
            'address_json->street_2'             => 'Third Floor',
            'address_json->street_number'        => 55,
            'address_json->street_number_suffix' => 'A',
            'address_json->postal_code'          => '1111AA',
            'address_json->city'                 => 'Amsterdam',
            'address_json->state_code'           => 'NH',
            'address_json->country_code'         => 'NL',
            'contact_json->first_name'           => 'Test',
            'contact_json->last_name'            => 'Tester',
            'contact_json->company'              => 'Acme Co.',
            'contact_json->email'                => 'user@example.com',
            'contact_json->phone_number'         => '12312312323',
        ]);
    }

    /** @test */
    public function testItUpdatesACollectionAndSendsItToTheCarrier()
    {
        $this->markTestSkipped('This test should be implemented in microservices for carriers that offer collections, not in the skeleton.');

        // TODO: Bind specific carrier response.
        $this->bindHttpClientMock();

        $uuid = Uuid::uuid4();

        $patchData = [
            'data' => [
                'type'       => 'collections',
                'id'         => $uuid,
                'attributes' => [
                    'name'     => 'Second Collection',
                    'register' => true,
                ],
            ],
        ];

        $response = $this->assertJsonSchema(
            "/collections/{collection_id}",
            "/collections/$uuid",
            $this->getRequestHeaders(),
            $patchData,
            'patch',
        );

        $this->assertDatabaseHas('collections', [
            'id'                                 => $response->json('data.id'),
            'myparcelcom_colletion_id'           => $uuid,
            'name'                               => 'Second Collection',
            'collection_time_from'               => Carbon::now(),
            'collection_time_to'                 => Carbon::now()->addHours(10),
            'address_json->street_1'             => 'My Street',
            'address_json->street_2'             => 'Third Floor',
            'address_json->street_number'        => 55,
            'address_json->street_number_suffix' => 'A',
            'address_json->postal_code'          => '1111AA',
            'address_json->city'                 => 'Amsterdam',
            'address_json->state_code'           => 'NH',
            'address_json->country_code'         => 'NL',
            'contact_json->first_name'           => 'Test',
            'contact_json->last_name'            => 'Tester',
            'contact_json->company'              => 'Acme Co.',
            'contact_json->email'                => 'user@example.com',
            'contact_json->phone_number'         => '12312312323',
        ]);

        // TODO: Assertion specific to the carrier response.
//        $this->assertNotEmpty($response->json('data.attributes.tracking_code'));
//        $this->assertNotEmpty($response->json('data.attributes.files'));
    }

    /** @test */
    public function itShouldRetrieveCollectionTimeSlots(): void
    {
        $this->markTestSkipped('This test should be implemented in microservices for carriers that offer collection time slots, not in the skeleton.');

        $this->assertJsonSchema(
            '/collection-time-slots',
            '/get-collection-time-slots?country_code=NL&postal_code=1111aa&date_from=2022-03-24T09:30:00+01:00&date_to=2022-03-24T10:30:00+01:00',
            $this->getRequestHeaders()
        );

        // todo: Assert data counts when time slots are available.
    }
}
