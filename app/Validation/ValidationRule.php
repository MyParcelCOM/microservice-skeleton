<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

abstract class ValidationRule implements RuleInterface
{
    /** @var string[] */
    protected $errors = [];

    /** @var string */
    protected $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param string   $path
     * @param stdClass $requestData
     * @return mixed
     */
    protected function getValueForPath(string $path, stdClass $requestData)
    {
        return (new PathResolver())->resolve($path, $requestData);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
