<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Rules\Sanitization;

interface SanitizationInterface
{
    public function sanitize(string $key, array $parameters): array;
}
