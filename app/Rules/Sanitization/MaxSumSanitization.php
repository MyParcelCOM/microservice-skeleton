<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxSumSanitization implements SanitizationInterface
{
    /** @var int|float */
    private $maxSum;
    /** @var array */
    private $fieldKeys;

    /**
     * @param int|float $maxSum
     * @param array $fieldKeys
     */
    public function __construct($maxSum, array $fieldKeys)
    {
        $this->maxSum = $maxSum;
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
        $itemKeys = [];
        foreach ($this->fieldKeys as $fieldKey) {
            $origValues = data_get($parameters, $fieldKey);
            if (is_array($origValues)) {
                $itemKeys += array_keys($origValues);
            } else {
                $itemKeys += [0];
            }
        }

        foreach ($itemKeys as $itemKey) {
            // Sum all values
            $sum = array_reduce($this->fieldKeys, function ($carry, $field) use ($parameters, $itemKey) {
                $field = str_replace('*', $itemKey, $field);
                return $carry + data_get($parameters, $field, 0);
            }, 0);

            // Correct individual fields if needed
            if ($sum > $this->maxSum) {
                foreach ($this->fieldKeys as $field) {
                    $field = str_replace('*', $itemKey, $field);
                    Arr::set($parameters, $field, strval(floor($this->maxSum / count($this->fieldKeys))));
                }
            }
        }

        return $parameters;
    }
}
