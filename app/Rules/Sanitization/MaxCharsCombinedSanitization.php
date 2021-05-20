<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxCharsCombinedSanitization implements SanitizationInterface
{
    /** @var int */
    private $maxChars;
    /** @var array */
    private $fieldKeys;
    /** @var string */
    private $spacer;

    /**
     * @param int $maxChars
     * @param array $fieldKeys
     * @param string $spacer
     */
    public function __construct(int $maxChars, array $fieldKeys, string $spacer = ' ')
    {
        $this->maxChars = $maxChars;
        $this->fieldKeys = $fieldKeys;
        $this->spacer = $spacer;
    }

    /**
     * Sanitize the incoming data.
     *
     * @param string $key
     * @param array $parameters
     * @return array $parameters
     */
    public function sanitize(string $key, array $parameters): array
    {
        $currLen = 0;
        foreach ($this->fieldKeys as $fieldKey) {
            if ($origValue = Arr::get($parameters, $fieldKey)) {
                // Limit to max length, keeping in mind combined length
                // Typecast to string, because it might be an integer (example: street number)
                $value = substr((string) $origValue, 0, $this->maxChars - $currLen);

                // Set the (possibly changed) value
                // Typecast back to integer if needed
                Arr::set($parameters, $fieldKey, is_int($origValue) ? (int) $value : $value);

                // Update combined length so far
                $currLen += strlen($value) + strlen($this->spacer);
            }
        }
        return $parameters;
    }
}
