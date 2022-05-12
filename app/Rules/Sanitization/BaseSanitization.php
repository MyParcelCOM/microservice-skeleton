<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationRuleParser;

abstract class BaseSanitization implements SanitizationInterface
{
    /**
     * @param string $key
     * @param array  $parameters
     * @param array  $shipmentRules
     * @return array
     */
    abstract public function sanitize(
        string $key,
        array $parameters,
        array $shipmentRules = []
    ): array;

    /**
     * @param string $key
     * @param array  $parameters
     * @param array  $keys
     * @return mixed
     */
    public function getKeys(string $key, array $parameters, array $keys = [])
    {
        if (!Str::contains($key, '*')) {
            return $key;
        }

        $values = data_get($parameters, Str::before($key, '.*'));
        foreach (array_keys($values) as $subkey) {
            $replacedKey = Str::replaceFirst('*', (string) $subkey, $key);
            if (Str::contains($replacedKey, '*')) {
                $keys = $this->getKeys($replacedKey, $parameters, $keys);
            } else {
                $keys[] = $replacedKey;
            }
        }
        return $keys;
    }

        /**
     * @param mixed $paramKey
     * @param mixed $fullKey
     * @param array $parameters
     * @param array $shipmentRules
     * @return bool
     */
    protected function isParameterOptional(
        $paramKey,
        $fullKey,
        array $parameters,
        array $shipmentRules
    ): bool {
        $validator = Validator::make($parameters, $shipmentRules);
        if ($validator instanceof \Illuminate\Validation\Validator) {
            $rules = Arr::get($validator->getRules(), $paramKey);
            $requiredRules = collect($rules)->filter(function ($rawRule) {
                $keepRules = [
                    'Required',
                    'RequiredIf',
                    'RequiredUnless',
                    'RequiredWith',
                    'RequiredWithAll',
                    'RequiredWithout',
                    'RequiredWithoutAll',
                ];
                [$rule] = ValidationRuleParser::parse($rawRule);
                return in_array($rule, $keepRules);
            })->toArray();
            $requiredValidator = Validator::make($parameters, [
                $fullKey => $requiredRules,
            ]);
            $isOptional = !$requiredValidator->fails();
            return $isOptional;
        }
        return true;
    }
}
