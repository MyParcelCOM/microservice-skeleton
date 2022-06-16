<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxCharsSanitization extends BaseSanitization
{
    /** @var int */
    private $maxChars;

    /**
     * @param int $maxChars
     */
    public function __construct(int $maxChars)
    {
        $this->maxChars = $maxChars;
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

                Arr::set($parameters, $singleKey, mb_substr((string) $singleValue, 0, $this->maxChars));
            }
        }
        return $parameters;
    }
}
