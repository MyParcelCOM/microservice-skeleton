<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

class CombinedFieldsMaxRule implements CustomRuleInterface
{
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        $maxLength = (int) array_shift($parameters);

        $requestBody = $validator->getData();

        $otherParams = array_map(function ($param) use ($requestBody) {
            return (string) Arr::get($requestBody, $param);
        }, $parameters);

        // Filter out empty strings.
        $filteredParams = array_filter($otherParams, 'mb_strlen');

        $totalLength = array_reduce($filteredParams, function ($totalLength, $param) {
            return $totalLength + mb_strlen($param);
        }, mb_strlen($value) + count($filteredParams));

        return $totalLength <= $maxLength;
    }

    /**
     * Replace the placeholders in the error messages with custom attribute names.
     */
    public function placeholders(string $message, string $attribute, string $rule, array $parameters): string
    {
        $maxLength = array_shift($parameters);
        $message = str_replace(':max', (string) $maxLength, $message);

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
