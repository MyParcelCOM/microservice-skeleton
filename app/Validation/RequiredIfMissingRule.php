<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class RequiredIfMissingRule extends ValidationRule implements RuleInterface
{
    /** @var string */
    protected $missingPath;
    /** @var string[] */
    protected $errors = [];

    public function __construct(string $requiredPath, string $missingPath)
    {
        parent::__construct($requiredPath);

        $this->missingPath = $missingPath;
    }

    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData)
    {
        $requiredValue = $this->getValueForPath($this->requiredPath, $requestData);
        $missingValue = $this->getValueForPath($this->missingPath, $requestData);

        if (!isset($missingValue) && !isset($requiredValue)) {
            $this->errors[] = "{$this->requiredPath} is required when {$this->missingPath} is missing on given request";

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