<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxCharsCombinedSanitization implements SanitizationInterface
{
    public function __construct(
        private readonly int $maxChars,
        private readonly array $fieldKeys,
        private readonly string $spacer = ' ',
    ) {
    }

    public function sanitize(string $key, array $parameters): array
    {
        $itemKeys = [];
        foreach ($this->fieldKeys as $fieldKey) {
            $origValues = data_get($parameters, $fieldKey);
            if (is_array($origValues)) {
                $itemKeys += array_keys($origValues);
            } else {
                $itemKeys += [0];
            }
        }

        foreach ($itemKeys as $itemKey) {
            $currLen = 0;
            foreach ($this->fieldKeys as $fieldKey) {
                $fieldKey = str_replace('*', (string) $itemKey, $fieldKey);
                if ($origValue = data_get($parameters, $fieldKey)) {
                    // Limit to max length, keeping in mind combined length
                    // Typecast to string, because it might be an integer (example: street number)
                    $value = mb_substr((string) $origValue, 0, max(0, $this->maxChars - $currLen));

                    // Set the (possibly changed) value
                    // Typecast back to integer if needed
                    Arr::set($parameters, $fieldKey, is_int($origValue) ? (int) $value : $value);

                    // Update combined length so far
                    $currLen += mb_strlen($value) + mb_strlen($this->spacer);
                }
            }
        }

        return $parameters;
    }
}
