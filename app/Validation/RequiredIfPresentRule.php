<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class RequiredIfPresentRule extends ValidationRule implements RuleInterface
{
    /** @var string */
    protected $presentPath;

    public function __construct(string $path, string $presentPath)
    {
        parent::__construct($path);

        $this->presentPath = $presentPath;
    }

    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData): bool
    {
        $requiredValue = $this->getValueForPath($this->path, $requestData);
        $presentValue = $this->getValueForPath($this->presentPath, $requestData);

        if (isset($presentValue) && !isset($requiredValue)) {
            $this->errors[] = "{$this->path} is required when {$this->presentPath} is set on given request";

            return false;
        }

        return true;
    }
}
