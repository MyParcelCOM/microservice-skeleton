<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

abstract class ValidationRule
{
    /** @var string */
    protected $requiredPath;

    /**
     * @param string $requiredRule
     */
    public function __construct(string $requiredRule)
    {
        $this->requiredPath = $requiredRule;
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
}
