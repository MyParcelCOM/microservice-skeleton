<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\Microservice\ServiceRates\Price;

class Option
{
    private string $code;
    private ?string $name = null;
    private ?array $values = null;
    private ?Price $price = null;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValues(): ?array
    {
        return $this->values;
    }

    public function setValues(?array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(?Price $price): self
    {
        $this->price = $price;

        return $this;
    }
}
