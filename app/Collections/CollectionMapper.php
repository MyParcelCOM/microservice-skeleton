<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use MyParcelCom\JsonApi\Exceptions\UnprocessableEntityException;
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

        if (Arr::has($attributes, 'collection_time.from')) {
            $timeFrom = Arr::get($attributes, 'collection_time.from');
            if (preg_match(
                '/^\d{4}-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1])T(?:[0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9](?:\.\d{3})?(Z|\+\d{2}:?\d{2})$/',
                (string) $timeFrom
            )) {
                $collection->setCollectionTimeFrom((new Carbon($timeFrom))->utc()->timestamp);
            } elseif (is_numeric($timeFrom)) {
                $collection->setCollectionTimeFrom($timeFrom);
            } else {
                throw new UnprocessableEntityException('The collection_time.from attribute is not a timestamp or an ISO 8601 formatted string.');
            }
        }

        if (Arr::has($attributes, 'collection_time.to')) {
            $timeTo = Arr::get($attributes, 'collection_time.to');
            if (preg_match(
                '/^\d{4}-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1])T(?:[0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9](?:\.\d{3})?(Z|\+\d{2}:?\d{2})$/',
                (string) $timeTo
            )) {
                $collection->setCollectionTimeTo((new Carbon($timeTo))->utc()->timestamp);
            } elseif (is_numeric($timeTo)) {
                $collection->setCollectionTimeTo($timeTo);
            } else {
                throw new UnprocessableEntityException('The collection_time.to attribute is not a timestamp or an ISO 8601 formatted string.');
            }
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
