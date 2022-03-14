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
     * Makes a collection from the posted collection data and persists it.
     *
     * @param array $data
     * @param array $meta
     */
    public function createFromPostData(array $data): Collection
    {
        $collection = $this->collectionMapper->map($data);

        $collection->save();

        return $collection;
    }
}
