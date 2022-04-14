<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;
use Ramsey\Uuid\Uuid;

class CollectionsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;
    // TODO: Uncomment this when implementing this.
//    use RefreshDatabase;

    /** @test */
    public function testItSavesACollectionInTheDatabase()
    {
        $this->markTestSkipped('This test should be implemented in microservices for carriers that offer collections, not in the skeleton.');

        $uuid = Uuid::uuid4();

        $postData = [
            'data' => [
                'type'       => 'collections',
                'attributes' => [
                    'myparcelcom_collection_id' => $uuid,
                    'name'                      => 'First Collection',
                    'collection_date'           => '2022-03-03',
                    'collection_time'           => [
                        'from' => '12:00',
                        'to'   => '20:00',
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
            'id'                            => $response->json('data.id'),
            'myparcelcom_colletion_id'      => $uuid,
            'name'                          => 'First Collection',
            'collection_date'               => '2022-03-03',
            'collection_time_from'          => '12:00',
            'collection_time_to'            => '20:00',
            'address->street_1'             => 'My Street',
            'address->street_2'             => 'Third Floor',
            'address->street_number'        => 55,
            'address->street_number_suffix' => 'A',
            'address->postal_code'          => '1111AA',
            'address->city'                 => 'Amsterdam',
            'address->state_code'           => 'NH',
            'address->country_code'         => 'NL',
            'address->first_name'           => 'Test',
            'address->last_name'            => 'Tester',
            'address->company'              => 'Acme Co.',
            'address->email'                => 'user@example.com',
            'address->phone_number'         => '12312312323',
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
            "/collections/${uuid}",
            $this->getRequestHeaders(),
            $patchData,
            'patch',
        );

        $this->assertDatabaseHas('collections', [
            'id'                            => $response->json('data.id'),
            'myparcelcom_colletion_id'      => $uuid,
            'name'                          => 'Second Collection',
            'collection_date'               => '2022-03-03',
            'collection_time_from'          => '12:00',
            'collection_time_to'            => '20:00',
            'address->street_1'             => 'My Street',
            'address->street_2'             => 'Third Floor',
            'address->street_number'        => 55,
            'address->street_number_suffix' => 'A',
            'address->postal_code'          => '1111AA',
            'address->city'                 => 'Amsterdam',
            'address->state_code'           => 'NH',
            'address->country_code'         => 'NL',
            'address->first_name'           => 'Test',
            'address->last_name'            => 'Tester',
            'address->company'              => 'Acme Co.',
            'address->email'                => 'user@example.com',
            'address->phone_number'         => '12312312323',
        ]);

        // TODO: Assertion specific to the carrier response.
//        $this->assertNotEmpty($response->json('data.attributes.tracking_code'));
//        $this->assertNotEmpty($response->json('data.attributes.files'));
    }
}
