<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class RequiredIfPresentRule extends ValidationRule implements RuleInterface
{
    /** @var string */
    protected $presentPath;

    public function __construct(string $requiredPath, string $presentPath)
    {
        parent::__construct($requiredPath);

        $this->presentPath = $presentPath;
    }

    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData): bool
    {
        $requiredValue = $this->getValueForPath($this->requiredPath, $requestData);
        $presentValue = $this->getValueForPath($this->presentPath, $requestData);

        if (isset($presentValue) && !isset($requiredValue)) {
            $this->errors[] = "{$this->requiredPath} is required when {$this->presentPath} is set on given request";

            return false;
        }

        return true;
    }
}
