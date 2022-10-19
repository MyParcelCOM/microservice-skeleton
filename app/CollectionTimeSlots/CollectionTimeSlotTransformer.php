<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;

class CollectionTimeSlotTransformer extends AbstractTransformer
{
    protected $type = 'collection-time-slots';

    /**
     * @param CollectionTimeSlot $model
     * @return string
     */
    public function getId($model)
    {
        $this->validateModel($model);

        return $model->getId();
    }

    /**
     * @param CollectionTimeSlot $model
     * @return array
     */
    public function getAttributes($model): array
    {
        $this->validateModel($model);

        return [
            'from' => $model->getFrom()->toIso8601String(),
            'to'   => $model->getTo()->toIso8601String(),
        ];
    }

    /**
     * @param CollectionTimeSlot $collectionTimeSlot
     * @throws ModelTypeException
     */
    protected function validateModel($collectionTimeSlot): void
    {
        if (!$collectionTimeSlot instanceof CollectionTimeSlot) {
            throw new ModelTypeException($collectionTimeSlot, 'collection-time-slots');
        }
    }
}
