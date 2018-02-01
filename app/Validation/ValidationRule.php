<?php

namespace MyParcelCom\Microservice\Validation;

abstract class ValidationRule
{
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
     * @param        $resource
     * @return mixed
     */
    protected function getValueForPath(string $path, $resource)
    {
        $pathArray = explode('.', $path);

        $requiredProperty = $resource;

        array_walk($pathArray, function ($attribute) use (&$requiredProperty) {
            $requiredProperty = $requiredProperty->$attribute ?? null;
        });

        return $requiredProperty;
    }
}
