<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

abstract class ValidationRule
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
     * @param string $path
     * @param stdClass $requestData
     * @return mixed
     */
    protected function getValueForPath(string $path, stdClass $requestData)
    {
        $pathArray = explode('.', $path);

        $requiredProperty = $requestData;

        array_walk($pathArray, function ($attribute) use (&$requiredProperty) {
            $requiredProperty = $requiredProperty->$attribute ?? null;
        });

        return $requiredProperty;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
