<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

interface RuleInterface
{
    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData): bool;

    /**
     * @return array
     */
    public function getErrors(): array;
}
