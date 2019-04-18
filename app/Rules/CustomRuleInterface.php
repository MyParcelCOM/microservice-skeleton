<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules;

use Illuminate\Validation\Validator;

interface CustomRuleInterface
{
    /**
     * Validate the incoming data according to the custom validation rule.
     *
     * @param string    $attribute
     * @param mixed     $value
     * @param array     $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validate(string $attribute, $value, array $parameters, Validator $validator): bool;
}
