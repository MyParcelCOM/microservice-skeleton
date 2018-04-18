<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class PathResolver
{
    /**
     * Find given path in given data and return the value found. Returns `null`
     * if the path doesn't exist or the value is `null`.
     *
     * @param string   $path
     * @param stdClass $data
     * @return mixed
     */
    public function resolve(string $path, stdClass $data)
    {
        $pathArray = explode('.', $path);

        $property = $data;

        foreach ($pathArray as $attribute) {
            $property = $property->$attribute ?? null;
        };

        return $property;
    }
}
