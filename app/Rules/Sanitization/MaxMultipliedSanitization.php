<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxMultipliedSanitization implements SanitizationInterface
{
    /** @var int|float */
    private $maxMultiplied;
    /** @var array */
    private $fieldKeys;

    /**
     * @param int|float $maxMultiplued
     * @param array $fieldKeys
     */
    public function __construct($maxMultiplied, array $fieldKeys)
    {
        $this->maxMultiplied = $maxMultiplied;
        $this->fieldKeys = $fieldKeys;
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
        // Multiply all values
        $multiplied = array_reduce($this->fieldKeys, function ($carry, $field) use ($parameters) {
            return $carry * Arr::get($parameters, $field, 1);
        }, 1);

        // Correct individual fields if needed
        if ($multiplied > $this->maxMultiplied) {
            foreach ($this->fieldKeys as $field) {
                Arr::set($parameters, $field, strval(floor($this->maxMultiplied ** (1.0 / count($this->fieldKeys)))));
            }
        }
        return $parameters;
    }
}
