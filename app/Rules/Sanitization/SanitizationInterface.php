<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

interface SanitizationInterface
{
    /**
     * Sanitize the incoming data.
     *
     * @param string $key
     * @param array  $parameters
     * @return array $parameters
     */
    public function sanitize(string $key, array $parameters): array;
}
