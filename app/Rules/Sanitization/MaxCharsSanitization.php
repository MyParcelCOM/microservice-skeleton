<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxCharsSanitization extends BaseSanitization
{
    public function __construct(
        private readonly int $maxChars,
    ) {
    }

    public function sanitize(
        string $key,
        array $parameters,
        array $shipmentRules = [],
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

                Arr::set($parameters, $singleKey, mb_substr((string) $singleValue, 0, $this->maxChars));
            }
        }

        return $parameters;
    }
}
