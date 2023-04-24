<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class PhysicalProperties
{
    protected ?int $height = null;
    protected ?int $width = null;
    protected ?int $length = null;
    protected ?float $volume = null;
    protected int $weight;
    protected ?int $volumetricWeight = null;

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getVolumetricWeight(): ?int
    {
        return $this->volumetricWeight;
    }

    public function setVolumetricWeight(?int $volumetricWeight): self
    {
        $this->volumetricWeight = $volumetricWeight;

        return $this;
    }
}
