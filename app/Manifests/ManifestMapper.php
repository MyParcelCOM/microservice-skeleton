<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

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
        return $manifest;
    }
}
