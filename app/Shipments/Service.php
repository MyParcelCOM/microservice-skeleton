<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class Service
{
    /** @var string */
    protected $code;

    /** @var string */
    protected $name;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): Service
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Service
    {
        $this->name = $name;

        return $this;
    }
}
