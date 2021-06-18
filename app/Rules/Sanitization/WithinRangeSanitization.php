<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class WithinRangeSanitization implements SanitizationInterface
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
     * @param array $parameters
     * @return array $parameters
     */
    public function sanitize(string $key, array $parameters): array
    {
        $values = data_get($parameters, $key);
        if ($values) {
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $index => $singleValue) {
                $singleKey = str_replace('*', $index, $key);
                $singleValue = max($singleValue, $this->minValue);
                $singleValue = min($singleValue, $this->maxValue);
                Arr::set($parameters, $singleKey, $singleValue);
            }
        }
        return $parameters;
    }
}
