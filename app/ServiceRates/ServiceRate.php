<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

class ServiceRate
{
    private ?string $code = null;
    private ?int $weightMin = null;
    private ?int $weightMax = null;
    private ?int $lengthMax = null;
    private ?int $widthMax = null;
    private ?int $heightMax = null;
    private ?float $volumeMax = null;
    private ?Price $fuelSurcharge = null;

    public function __construct(
        private Price $price,
        private Price $purchasePrice,
    ) {
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setWeightMin(?int $weightMin): self
    {
        $this->weightMin = $weightMin;

        return $this;
    }

    public function getWeightMin(): ?int
    {
        return $this->weightMin;
    }

    public function setWeightMax(?int $weightMax): self
    {
        $this->weightMax = $weightMax;

        return $this;
    }

    public function getWeightMax(): ?int
    {
        return $this->weightMax;
    }

    public function setLengthMax(?int $lengthMax): self
    {
        $this->lengthMax = $lengthMax;

        return $this;
    }

    public function getLengthMax(): ?int
    {
        return $this->lengthMax;
    }

    public function setWidthMax(?int $widthMax): self
    {
        $this->widthMax = $widthMax;

        return $this;
    }

    public function getWidthMax(): ?int
    {
        return $this->widthMax;
    }

    public function setHeightMax(?int $heightMax): self
    {
        $this->heightMax = $heightMax;

        return $this;
    }

    public function getHeightMax(): ?int
    {
        return $this->heightMax;
    }

    public function setVolumeMax(?float $volumeMax): self
    {
        $this->volumeMax = $volumeMax;

        return $this;
    }

    public function getVolumeMax(): ?float
    {
        return $this->volumeMax;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getPurchasePrice(): Price
    {
        return $this->purchasePrice;
    }

    public function setFuelSurcharge(?Price $price): self
    {
        $this->fuelSurcharge = $price;

        return $this;
    }

    public function getFuelSurcharge(): ?Price
    {
        return $this->fuelSurcharge;
    }
}
