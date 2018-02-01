<?php

namespace MyParcelCom\Microservice\Validation;

class ConditionalRequiredRule extends ValidationRule implements RuleInterface
{
    /** @var string[] */
    protected $errors = [];

    /**
     * @param $resource
     * @return bool
     */
    public function isValid($resource)
    {
        // TODO: Implement isValid() method.
    }

    /**
     * @return array[]
     */
    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }
}
