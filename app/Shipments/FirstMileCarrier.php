<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class FirstMileCarrier
{

    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

}
