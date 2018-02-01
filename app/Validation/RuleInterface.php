<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

interface RuleInterface
{
     /**
     * @param $resource
     * @return bool
     */
    public function isValid($resource);

    /**
     * @return array[]
     */
    public function getErrors();
}
