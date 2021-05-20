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
        $value = Arr::get($parameters, $key);
        if ($value) {
            $value = max($value, $this->minValue);
            $value = min($value, $this->maxValue);
            Arr::set($parameters, $key, $value);
        }
        return $parameters;
    }
}
