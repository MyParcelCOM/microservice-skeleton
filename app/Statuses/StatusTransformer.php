<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;

class StatusTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'statuses';

    /**
     * @param Status $status
     * @return string
     */
    public function getId($status): string
    {
        $this->validateModel($status);

        return $status->getId();
    }

    /**
     * @param Status $status
     * @return array
     */
    public function getAttributes($status): array
    {
        $this->validateModel($status);

        return array_filter([
                'myparcelcom_code' => $status->getCode(),
            ]) + [
                'description'         => $status->getDescription(),
                'category'            => $status->getCategory(),
                'timestamp'           => $status->getTimestamp(),
                'physical_properties' => $status->getPhysicalProperties() === null ? null : array_filter([
                    'height'            => $status->getPhysicalProperties()->getHeight(),
                    'width'             => $status->getPhysicalProperties()->getWidth(),
                    'length'            => $status->getPhysicalProperties()->getLength(),
                    'volume'            => $status->getPhysicalProperties()->getVolume(),
                    'weight'            => $status->getPhysicalProperties()->getWeight(),
                    'volumetric_weight' => $status->getPhysicalProperties()->getVolumetricWeight(),
                ]),
                'new_tracking_code'   => $status->getNewTrackingCode(),
            ];
    }

    /**
     * @param Status $status
     * @throws ModelTypeException
     */
    protected function validateModel($status): void
    {
        if (!$status instanceof Status) {
            throw new ModelTypeException($status, 'statuses');
        }
    }
}
