<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

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
     * @param Collection $collection
     * @param array      $data
     *
     * @return Collection
     */
    public function updateFromPatchData(Collection $collection, array $data): Collection
    {
        // Grab collection from DB

        // Use mapper to update collection

        // If register: true in request data, register the collection with the carrier.

        return $collection;
    }
}
