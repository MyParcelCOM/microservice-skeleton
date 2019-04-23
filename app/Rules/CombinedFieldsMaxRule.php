<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

class CombinedFieldsMaxRule implements CustomRuleInterface
{
    /**
     * @inheritDoc
     */
    public function validate(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        $maxLength = (int) array_shift($parameters);

        $requestBody = $validator->getData();

        $otherParams = array_map(function ($param) use ($requestBody) {
            return (string) Arr::get($requestBody, $param);
        }, $parameters);

        // Filter out empty strings.
        $filteredParams = array_filter($otherParams, 'strlen');

        $totalLength = array_reduce($filteredParams, function ($totalLength, $param) {
            return $totalLength + strlen($param);
        }, strlen($value) + count($filteredParams));

        return $totalLength <= $maxLength;
    }

    /**
     * Replace the placeholders in the error messages with custom attribute names.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     * @return string
     */
    public function placeholders(string $message, string $attribute, string $rule, array $parameters): string
    {
        $maxLength = array_shift($parameters);
        $message = str_replace(':max', $maxLength, $message);

        $listOfCustomAttributeNames = Lang::get('validation.attributes');

        $niceAttributeNames = array_map(function ($attribute) use ($listOfCustomAttributeNames) {
            return Arr::get($listOfCustomAttributeNames, $attribute) ?? $attribute;
        }, $parameters);

        $totalAttributes = count($niceAttributeNames);
        $otherParams = $totalAttributes
            ? implode(', ', array_slice($niceAttributeNames, 0, $totalAttributes - 1)) . ' and ' . end($niceAttributeNames)
            : implode(', ', $niceAttributeNames);

        return str_replace(':others', $otherParams, $message);
    }
}
