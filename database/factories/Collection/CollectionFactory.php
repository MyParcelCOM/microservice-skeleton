<?php

namespace Database\Factories\Collection;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use MyParcelCom\Microservice\Collections\Collection;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    /** @throws UnknownProperties */
    public function definition(): array
    {
        return [
            'name'                      => $this->faker->name(),
            'address_json'              => new AddressJson([
                'street_1'      => $this->faker->streetAddress(),
                'street_2'      => null,
                'street_number' => $this->faker->buildingNumber(),
                'postal_code'   => $this->faker->postcode(),
                'city'          => $this->faker->city(),
                'country_code'  => $this->faker->countryCode(),
            ]),
            'contact_json'              => new ContactJson([
                'first_name'   => $this->faker->firstName(),
                'last_name'    => $this->faker->lastName(),
                'phone_number' => $this->faker->phoneNumber(),
            ]),
            'tracking_code'             => null,
            'registered_at'             => null,
            'collection_time_from'      => Carbon::now()->addDay(),
            'collection_time_to'        => Carbon::now()->addDay()->addHours(4),
            'myparcelcom_collection_id' => $this->faker->uuid(),
            'created_at'                => now(),
            'updated_at'                => now(),
        ];
    }
}
