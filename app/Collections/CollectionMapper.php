<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Interfaces\MapperInterface;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;

class CollectionMapper implements MapperInterface
{
    /**
     * @param array           $data
     * @param Collection|null $collection
     * @return Collection
     */
    public function map($data, $collection = null): Collection
    {
        $attributes = Arr::get($data, 'attributes', $data);

        $collection = $collection ?? new Collection();

        if (Arr::has($attributes, 'myparcelcom_collection_id')) {
            $collection->setMyparcelcomCollectionId(Arr::get($attributes, 'myparcelcom_collection_id'));
        }

        if (Arr::has($attributes, 'name')) {
            $collection->setName(Arr::get($attributes, 'name'));
        }

        if (Arr::has($attributes, 'collection_date')) {
            $collection->setCollectionDate(Arr::get($attributes, 'collection_date'));
        }

        if (Arr::has($attributes, 'collection_time.from')) {
            $collection->setCollectionTimeFrom(Arr::get($attributes, 'collection_time.from'));
        }

        if (Arr::has($attributes, 'collection_time.to')) {
            $collection->setCollectionTimeTo(Arr::get($attributes, 'collection_time.to'));
        }

        if (Arr::has($attributes, 'address')) {
            $collection->setAddressJson(new AddressJson(Arr::get($attributes, 'address')));
            $collection->setContactJson(new ContactJson(Arr::get($attributes, 'address')));
        }

        if (Arr::has($attributes, 'tracking_code')) {
            $collection->setTrackingCode(Arr::get($attributes, 'tracking_code'));
        }

        return $collection;
    }
}
