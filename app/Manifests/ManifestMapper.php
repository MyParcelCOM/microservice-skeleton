<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Interfaces\MapperInterface;

class ManifestMapper implements MapperInterface
{
    /**
     * @param array    $data
     * @param Manifest $manifest
     * @return Manifest
     */
    public function map($data, $manifest): Manifest
    {
        $attributes = Arr::get($data, 'attributes', $data);

        return $manifest;
    }
}
