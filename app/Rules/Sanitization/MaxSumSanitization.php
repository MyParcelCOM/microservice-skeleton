<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxSumSanitization implements SanitizationInterface
{
    public function __construct(
        private readonly int|float $maxSum,
        private readonly array $fieldKeys,
    ) {
    }

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
                $field = str_replace('*', (string) $itemKey, $field);

                return $carry + data_get($parameters, $field, 0);
            }, 0);

            // Correct individual fields if needed
            if ($sum > $this->maxSum) {
                foreach ($this->fieldKeys as $field) {
                    $field = str_replace('*', (string) $itemKey, $field);
                    Arr::set($parameters, $field, strval(floor($this->maxSum / count($this->fieldKeys))));
                }
            }
        }

        return $parameters;
    }
}
