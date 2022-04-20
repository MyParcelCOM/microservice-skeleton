<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\Microservice\Shipments\File;

class CollectionTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'collections';

    /**
     * @param Collection $collection
     * @return string
     */
    public function getId($collection): string
    {
        return $collection->getId();
    }

    /**
     * @param Collection $collection
     * @return array
     */
    public function getAttributes($collection): array
    {
        $this->validateModel($collection);

        $attributes = array_filter([
            'name'                      => $collection->getName(),
            'myparcelcom_collection_id' => $collection->getMyparcelcomCollectionId(),
            'collection_time'           => [
                'from' => $collection->getCollectionTimeFrom()->getTimestamp(),
                'to'   => $collection->getCollectionTimeTo()->getTimestamp(),
            ],
            'address'                   => $collection->getAddressJson()->toArrayWith($collection->getContactJson(), true),
            'tracking_code'             => $collection->getTrackingCode(),
        ]);

        if ($collection->getRegisteredAt()) {
            $attributes['registered_at'] = $collection->getRegisteredAt()->getTimestamp();
        }

        if ($collection->getFiles()) {
            $attributes['files'] = $collection->getFiles()->map(function (File $file) {
                return [
                    'resource_type' => $file->getType(),
                    'mime_type'     => $file->getMimeType(),
                    'extension'     => $file->getExtension(),
                    'data'          => $file->getData(),
                ];
            })->all();
        }

        return $attributes;
    }

    /**
     * @param Collection $collection
     * @throws ModelTypeException
     */
    protected function validateModel($collection): void
    {
        if (!$collection instanceof Collection) {
            throw new ModelTypeException($collection, 'collections');
        }
    }
}
