<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\Microservice\Shipments\Option;

class ServiceRateTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'service-rates';

    /**
     * @param ServiceRate $serviceRate
     * @return string|null
     */
    public function getId($serviceRate): ?string
    {
        $this->validateModel($serviceRate);

        return null;
    }

    /**
     * @param ServiceRate $serviceRate
     * @return array
     */
    public function getAttributes($serviceRate): array
    {
        $this->validateModel($serviceRate);

        return array_filter([
            'code'           => $serviceRate->getCode(),
            'weight_min'     => $serviceRate->getWeightMin(),
            'weight_max'     => $serviceRate->getWeightMax(),
            'length_max'     => $serviceRate->getLengthMax(),
            'width_max'      => $serviceRate->getWidthMax(),
            'height_max'     => $serviceRate->getHeightMax(),
            'volume_max'     => $serviceRate->getVolumeMax(),
            'price'          => array_filter([
                'amount'   => $serviceRate->getPrice()->getAmount(),
                'currency' => $serviceRate->getPrice()->getCurrency(),
            ]),
            'purchase_price' => array_filter([
                'amount'   => $serviceRate->getPurchasePrice()->getAmount(),
                'currency' => $serviceRate->getPurchasePrice()->getCurrency(),
            ]),
            'fuel_surcharge' => $serviceRate->getFuelSurcharge() === null ? null : array_filter([
                'amount'   => $serviceRate->getFuelSurcharge()->getAmount(),
                'currency' => $serviceRate->getFuelSurcharge()->getCurrency(),
            ]),
            'transit_time'   => array_filter([
                'min' => $serviceRate->getTransitTimeMin(),
                'max' => $serviceRate->getTransitTimeMax(),
            ]),
            'options'        => array_map(function (Option $option) {
                return array_filter([
                    'code'   => $option->getCode(),
                    'name'   => $option->getName(),
                    'values' => $option->getValues() === null ? null : array_filter($option->getValues()),
                    'price'  => $option->getPrice() === null ? null : array_filter([
                        'amount'   => $option->getPrice()->getAmount(),
                        'currency' => $option->getPrice()->getCurrency(),
                    ]),
                ]);
            }, $serviceRate->getOptions()),
        ]);
    }

    /**
     * @param ServiceRate $serviceRate
     * @throws ModelTypeException
     */
    protected function validateModel($serviceRate): void
    {
        if (!$serviceRate instanceof ServiceRate) {
            throw new ModelTypeException($serviceRate, 'service rates');
        }
    }
}
