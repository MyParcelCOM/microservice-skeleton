<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;

class ServiceRateTransformer extends AbstractTransformer {

    /** @var string */
    protected $type = 'service_rates';

    /**
     * @param mixed $serviceRate
     * @return string
     */
    public function getId($serviceRate): string
    {
        $this->validateModel($serviceRate);

        return $serviceRate->getId();
    }

    /**
     * @param mixed $serviceRate
     * @return array
     */
    public function getAttributes($serviceRate): array
    {
        $this->validateModel($serviceRate);
        return parent::getAttributes($serviceRate);
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
