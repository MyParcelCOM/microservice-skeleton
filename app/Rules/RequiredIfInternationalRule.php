<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class RequiredIfInternationalRule implements CustomRuleInterface
{
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        if ($this->isInternational($validator->getData())) {
            return isset($value);
        }

        return true;
    }

    private function isInternational(array $data): bool
    {
        $recipientCountryCode = Arr::get($data, 'data.attributes.recipient_address.country_code');
        $senderCountryCode = Arr::get($data, 'data.attributes.sender_address.country_code');

        return $recipientCountryCode !== $senderCountryCode;
    }
}
