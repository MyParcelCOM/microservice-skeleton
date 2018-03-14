<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use MyParcelCom\Microservice\Validation\Conditions\ConditionInterface;
use stdClass;

class RequiredIfNotCondition extends ValidationRule implements RuleInterface
{
    /** @var ConditionInterface */
    protected $condition;

    public function __construct(string $path, ConditionInterface $condition)
    {
        parent::__construct($path);

        $this->condition = $condition;
    }

    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData): bool
    {
        $requiredValue = $this->getValueForPath($this->path, $requestData);

        if (!$this->condition->meetsCondition($requestData) && $requiredValue === null) {
            $this->errors[] = "{$this->path} is required when condition `{$this->condition}` is NOT met";

            return false;
        }

        return true;
    }
}
