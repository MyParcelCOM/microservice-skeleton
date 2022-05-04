<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class WithinRangeSanitization extends BaseSanitization
{
    /** @var int|float */
    private $minValue;
    /** @var int|float */
    private $maxValue;

    /**
     * @param int|float $minValue
     * @param int|float $maxValue
     */
    public function __construct($minValue, $maxValue)
    {
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
    }

    /**
     * Sanitize the incoming data.
     *
     * @param string $key
     * @param array  $parameters
     * @param array  $shipmentRules
     * @return array $parameters
     */
    public function sanitize(
        string $key,
        array $parameters,
        array $shipmentRules = []
    ): array {
        $values = data_get($parameters, $key);
        if ($values) {
            if (!is_array($values)) {
                $values = [$values];
            }
            $keyedValues = collect($this->getKeys($key, $parameters))->combine($values)->toArray();
            foreach ($keyedValues as $singleKey => $singleValue) {
                // Prevent sanitization for optional empty fields
                if (empty($singleValue) && $this->isParameterOptional($singleKey, $key, $parameters, $shipmentRules)) {
                    continue;
                }

                $singleValue = max($singleValue, $this->minValue);
                $singleValue = min($singleValue, $this->maxValue);
                Arr::set($parameters, $singleKey, $singleValue);
            }
        }
        return $parameters;
    }
}
