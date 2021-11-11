<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use TypeError;

class ServiceRate
{
    protected string $id;

    protected string $code;

    protected int $weight_min;

    protected int $weight_max;

    protected int $length_max;

    protected int $width_max;

    protected int $height_max;

    protected float $volume_max;

    protected Price $price;

    protected Price $purchase_price;

    protected Price $fuel_surcharge;

    /**
     * @param int $weightMin
     * @return $this
     */
    public function setWeightMin(int $weightMin): self
    {
        $this->weight_min = $weightMin;

        return $this;
    }

    /**
     * @return int
     * @throws TypeError
     */
    public function getWeightMin(): int
    {
        if ($this->weight_min === null) {
            throw new TypeError('weight_min cant be null');
        }

        return $this->weight_min;
    }

    /**
     * @param int $weightMax
     * @return $this
     */
    public function setWeightMax(int $weightMax): self
    {
        $this->weight_max = $weightMax;

        return $this;
    }

    /**
     * @return int
     * @throws TypeError
     */
    public function getWeightMax(): int
    {
        if ($this->weight_max === null) {
            throw new TypeError('weight_max cant be null');
        }

        return $this->weight_max;
    }

    /**
     * @param int|null $lengthMax
     * @return $this
     */
    public function setLengthMax(?int $lengthMax): self
    {
        $this->length_max = $lengthMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLengthMax(): ?int
    {
        return $this->length_max;
    }

    /**
     * @param int|null $widthMax
     * @return $this
     */
    public function setWidthMax(?int $widthMax): self
    {
        $this->width_max = $widthMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidthMax(): ?int
    {
        return $this->width_max;
    }

    /**
     * @param int|null $heightMax
     * @return $this
     */
    public function setHeightMax(?int $heightMax): self
    {
        $this->height_max = $heightMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeightMax(): ?int
    {
        return $this->height_max;
    }

    /**
     * @param float|null $volumeMax
     * @return $this
     */
    public function setVolumeMax(?float $volumeMax): self
    {
        $this->volume_max = $volumeMax;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getVolumeMax(): ?float
    {
        return $this->volume_max;
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
        $this->purchase_price = $price;

        return $this;
    }

    /**
     * @return Price
     */
    public function getPurchasePrice(): Price
    {
        return $this->purchase_price;
    }

    /**
     * @param Price $price
     * @return $this
     */
    public function setFuelSurcharge(Price $price): self
    {
        $this->fuel_surcharge = $price;

        return $this;
    }

    /**
     * @return Price
     */
    public function getFuelSurcharge(): Price
    {
        return $this->fuel_surcharge;
    }

}
