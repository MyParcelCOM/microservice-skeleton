<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class PhysicalProperties
{
    /** @var int|null */
    protected $height;

    /** @var int|null */
    protected $width;

    /** @var int|null */
    protected $length;

    /** @var float|null */
    protected $volume;

    /** @var int */
    protected $weight;

    /** @var int */
    protected $volumetricWeight;

    /**
     * @return int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     * @return $this
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     * @return $this
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|null $length
     * @return $this
     */
    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return float
     */
    public function getVolume(): ?float
    {
        return $this->volume;
    }

    /**
     * @param float|null $volume
     * @return $this
     */
    public function setVolume(?float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return $this
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getVolumetricWeight(): int
    {
        return $this->volumetricWeight;
    }

    /**
     * @param int $volumetricWeight
     * @return $this
     */
    public function setVolumetricWeight(int $volumetricWeight): self
    {
        $this->volumetricWeight = $volumetricWeight;

        return $this;
    }
}
