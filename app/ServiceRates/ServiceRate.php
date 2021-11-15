<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

class ServiceRate
{
    /** @var string|null */
    private $code;

    /** @var int|null */
    private $weightMin;

    /** @var int|null */
    private $weightMax;

    /** @var int|null */
    private $lengthMax;

    /** @var int|null */
    private $widthMax;

    /** @var int|null */
    private $heightMax;

    /** @var float|null */
    private $volumeMax;

    /** @var Price */
    private $price;

    /** @var Price */
    private $purchasePrice;

    /** @var Price|null */
    private $fuelSurcharge;

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
     * @param Price $price
     * @return $this
     */
    public function setPrice(Price $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price
     * @return $this
     */
    public function setPurchasePrice(Price $price): self
    {
        $this->purchasePrice = $price;

        return $this;
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
