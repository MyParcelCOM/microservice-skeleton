<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class RequiredIfPresentRule extends ValidationRule implements RuleInterface
{
    /** @var string[] */
    protected $errors = [];
    /** @var string */
    protected $presentPath;

    public function __construct(string $requiredRule, string $conditionalPath)
    {
        parent::__construct($requiredRule);

        $this->presentPath = $conditionalPath;
    }

    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData)
    {
        $requiredValue = $this->getValueForPath($this->requiredPath, $requestData);
        $presentValue = $this->getValueForPath($this->presentPath, $requestData);

        if (isset($presentValue) && !isset($requiredValue)) {
            $this->errors[] = "{$this->requiredPath} path is required when {$this->presentPath} is set on given request";

            return false;
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
