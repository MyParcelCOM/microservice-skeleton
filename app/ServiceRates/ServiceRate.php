<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

class ServiceRate
{
    private ?string $code;
    private ?int $weightMin;
    private ?int $weightMax;
    private ?int $lengthMax;
    private ?int $widthMax;
    private ?int $heightMax;
    private ?float $volumeMax;
    private ?Price $fuelSurcharge;

    /**
     * @param Price $price
     * @param Price $purchasePrice
     */
    public function __construct(private Price $price, private Price $purchasePrice)
    {
    }

    /**
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param int|null $weightMin
     * @return $this
     */
    public function setWeightMin(?int $weightMin): self
    {
        $this->weightMin = $weightMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeightMin(): ?int
    {
        return $this->weightMin;
    }

    /**
     * @param int|null $weightMax
     * @return $this
     */
    public function setWeightMax(?int $weightMax): self
    {
        $this->weightMax = $weightMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeightMax(): ?int
    {
        return $this->weightMax;
    }

    /**
     * @param int|null $lengthMax
     * @return $this
     */
    public function setLengthMax(?int $lengthMax): self
    {
        $this->lengthMax = $lengthMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLengthMax(): ?int
    {
        return $this->lengthMax;
    }

    /**
     * @param int|null $widthMax
     * @return $this
     */
    public function setWidthMax(?int $widthMax): self
    {
        $this->widthMax = $widthMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidthMax(): ?int
    {
        return $this->widthMax;
    }

    /**
     * @param int|null $heightMax
     * @return $this
     */
    public function setHeightMax(?int $heightMax): self
    {
        $this->heightMax = $heightMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeightMax(): ?int
    {
        return $this->heightMax;
    }

    /**
     * @param float|null $volumeMax
     * @return $this
     */
    public function setVolumeMax(?float $volumeMax): self
    {
        $this->volumeMax = $volumeMax;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getVolumeMax(): ?float
    {
        return $this->volumeMax;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @return Price
     */
    public function getPurchasePrice(): Price
    {
        return $this->purchasePrice;
    }

    /**
     * @param Price|null $price
     * @return $this
     */
    public function setFuelSurcharge(?Price $price): self
    {
        $this->fuelSurcharge = $price;

        return $this;
    }

    /**
     * @return Price|null
     */
    public function getFuelSurcharge(): ?Price
    {
        return $this->fuelSurcharge;
    }
}
