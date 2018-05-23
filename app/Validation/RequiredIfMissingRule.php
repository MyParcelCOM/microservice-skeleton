<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class RequiredIfMissingRule extends ValidationRule implements RuleInterface
{
    /** @var string */
    protected $missingPath;

    public function __construct(string $path, string $missingPath)
    {
        parent::__construct($path);

        $this->missingPath = $missingPath;
    }

    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData): bool
    {
        $requiredValue = $this->getValueForPath($this->path, $requestData);
        $missingValue = $this->getValueForPath($this->missingPath, $requestData);

        if (!isset($missingValue) && !isset($requiredValue)) {
            $this->errors[] = "{$this->path} is required when {$this->missingPath} is missing on given request";

            return false;
        }

        return true;
    }
}
