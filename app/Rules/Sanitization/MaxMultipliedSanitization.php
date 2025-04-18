<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxMultipliedSanitization implements SanitizationInterface
{
    public function __construct(
        private readonly int|float $maxMultiplied,
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
            // Multiply all values
            $multiplied = array_reduce($this->fieldKeys, function ($carry, $field) use ($parameters, $itemKey) {
                $field = str_replace('*', (string) $itemKey, $field);

                return $carry * Arr::get($parameters, $field, 1);
            }, 1);

            // Correct individual fields if needed
            if ($multiplied > $this->maxMultiplied) {
                foreach ($this->fieldKeys as $field) {
                    $field = str_replace('*', (string) $itemKey, $field);
                    Arr::set($parameters, $field, strval(floor($this->maxMultiplied ** (1.0 / count($this->fieldKeys)))));
                }
            }
        }

        return $parameters;
    }
}
