<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class RequiredIfInternationalRule implements CustomRuleInterface
{
    /**
     * @inheritDoc
     */
    public function validate(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        if ($this->isInternational($validator->getData())) {
            return isset($value);
        }

        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isInternational(array $data): bool
    {
        return Arr::get($data, 'data.attributes.recipient_address.country_code') !== Arr::get($data, 'data.attributes.sender_address.country_code');
    }
}
