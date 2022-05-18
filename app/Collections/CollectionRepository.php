<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Exceptions\ResourceNotFoundException;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class CollectionRepository
{
    /**
     * @param CarrierApiGatewayInterface $carrierApiGateway
     * @param CollectionMapper           $collectionMapper
     */
    public function __construct(
        private CarrierApiGatewayInterface $carrierApiGateway,
        private CollectionMapper $collectionMapper,
    ) {
    }

    /**
     * Makes a Collection from the posted collection data and persists it.
     *
     * @param array $data
     *
     * @return Collection
     */
    public function createFromPostData(array $data): Collection
    {
        $collection = $this->collectionMapper->map($data);
        $collection->save();

        return $collection;
    }

    /**
     * Updates a Collection and registers it with the carrier if necessary.
     *
     * @param string $collectionId
     * @param array  $data
     *
     * @return Collection
     */
    public function updateFromPatchData(string $collectionId, array $data): Collection
    {
        /** @var Collection $collection */
        $collection = Collection::query()->whereKey($collectionId)->first();

        if (!$collection) {
            throw new ResourceNotFoundException('collections');
        }

        $updatedCollection = $this->collectionMapper->map($data, $collection);

        if (Arr::get($data, 'attributes.register') === true) {
            // TODO: Use the CarrierApiGateway to register the collection with the carrier API

            // TODO: If applicable, map tracking code and files to the Collection resource.

            $updatedCollection->setRegisteredAt(Carbon::now());

            $updatedCollection->save();
        }

        return $updatedCollection;
    }

    public function delete(string $collectionId): self
    {
        /** @var Collection $collection */
        $collection = Collection::query()->whereKey($collectionId)->first();

        if (!$collection) {
            throw new ResourceNotFoundException('collections');
        }

        $collection->delete();

        return $this;
    }
}
