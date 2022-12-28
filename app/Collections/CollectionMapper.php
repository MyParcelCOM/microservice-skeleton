<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Interfaces\MapperInterface;
use MyParcelCom\Microservice\Helpers\DateHelper;
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
        $relationships = Arr::get($data, 'relationships');

        $collection = $collection ?? new Collection();

        if (Arr::has($attributes, 'myparcelcom_collection_id')) {
            $collection->setMyparcelcomCollectionId(Arr::get($attributes, 'myparcelcom_collection_id'));
        }

        if (Arr::has($attributes, 'name')) {
            $collection->setName(Arr::get($attributes, 'name'));
        }

        if (Arr::has($attributes, 'collection_time')) {
            $timeTo = DateHelper::convertIsoOrTimestampToCarbon(Arr::get($attributes, 'collection_time.to'));
            $timeFrom = DateHelper::convertIsoOrTimestampToCarbon(Arr::get($attributes, 'collection_time.from'));

            $collection->setCollectionTimeFrom($timeFrom);
            $collection->setCollectionTimeTo($timeTo);
        }

        if (Arr::has($attributes, 'address')) {
            $collection->setAddressJson(new AddressJson(Arr::get($attributes, 'address')));
            $collection->setContactJson(new ContactJson(Arr::get($attributes, 'address')));
        }

        if (Arr::has($attributes, 'tracking_code')) {
            $collection->setTrackingCode(Arr::get($attributes, 'tracking_code'));
        }

        if (isset($relationships)) {
            $this->mapRelationships($relationships, $collection);
        }

        return $collection;
    }

    /**
     * @param array      $relationships
     * @param Collection $collection
     * @return $this
     */
    private function mapRelationships(array $relationships, Collection $collection): void
    {
//        if (array_key_exists('shipments', $relationships)) {
//            $shipments = array_map(function ($shipment) {
//                // todo: implement functionality to retrieve shipment based on shipment id.
//                return $shipment['id'];
//            }, Arr::get($relationships, 'shipments.data'));
//            $collection->shipments()->saveMany($shipments);
//        }
    }
}
