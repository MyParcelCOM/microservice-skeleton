<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class NullifyIfInvalidSanitization extends BaseSanitization
{
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

                // Perform original validation on single value
                $data = [];
                Arr::set($data, $singleKey, $singleValue);
                $validator = Validator::make(
                    $data,
                    Arr::only($shipmentRules, $key)
                );

                // Nullify value if validation fails
                if ($validator->fails()) {
                    Arr::set($parameters, $singleKey, null);
                }
            }
        }
        return $parameters;
    }
}
