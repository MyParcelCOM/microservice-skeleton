<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

use Illuminate\Support\Arr;

class MaxCharsSanitization implements SanitizationInterface
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
     * @param array $parameters
     * @return array $parameters
     */
    public function sanitize(string $key, array $parameters): array
    {
        $value = Arr::get($parameters, $key);
        if ($value) {
            Arr::set($parameters, $key, substr($value, 0, $this->maxChars));
        }
        return $parameters;
    }
}
