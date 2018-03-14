<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation\Conditions;

use stdClass;

interface ConditionInterface
{
    /**
     * @param stdClass $data
     * @return bool
     */
    public function meetsCondition(stdClass $data): bool;

    /**
     * @return string
     */
    public function __toString();
}
