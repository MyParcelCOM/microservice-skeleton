<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

class Manifest
{

    /**
     * @param string $name
     */
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
