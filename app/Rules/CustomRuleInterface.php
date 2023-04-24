<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules;

use Illuminate\Validation\Validator;

interface CustomRuleInterface
{
    /**
     * Validate the incoming data according to the custom validation rule.
     */
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool;
}
