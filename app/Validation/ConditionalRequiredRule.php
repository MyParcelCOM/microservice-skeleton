<?php

namespace MyParcelCom\Microservice\Validation;

class ConditionalRequiredRule extends ValidationRule implements RuleInterface
{
    /** @var string[] */
    protected $errors = [];
    /** @var string */
    protected $conditionalPath;

    public function __construct(string $path, string $conditionalPath)
    {
        parent::__construct($path);

        $this->conditionalPath = $conditionalPath;
    }

    /**
     * @param $resource
     * @return bool
     */
    public function isValid($resource)
    {
        $requiredValue = $this->getValueForPath($this->path, $resource);
        $conditionalValue = $this->getValueForPath($this->conditionalPath, $resource);

        if (isset($conditionalValue) && !isset($requiredValue)) {
            $this->errors[] = "{$this->path} property is required when {$this->conditionalPath} is set on given resource object.";

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
