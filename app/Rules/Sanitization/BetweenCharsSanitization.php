<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class BetweenCharsSanitization extends BaseSanitization
{
    /**
     * @param int $maxChars
     */
    public function __construct(
        private int $minChars,
        private int $maxChars
    ) {
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

                Arr::set($parameters, $singleKey, substr((string) $singleValue, 0, $this->maxChars));

                if (strlen(Arr::get($parameters, $singleKey)) < $this->minChars) {
                    $additionalChars = $this->minChars - strlen(Arr::get($parameters, $singleKey));
                    $value = Arr::get($parameters, $singleKey);
                    Arr::set($parameters, $singleKey, $value . str_repeat('X', $additionalChars));
                }
            }
        }
        return $parameters;
    }
}
