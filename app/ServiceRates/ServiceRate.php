<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

class ServiceRate
{
    /** @var string */
    protected $code;

    /** @var int */
    protected $weight_min;

    /** @var int */
    protected $weight_max;

    /** @var int */
    protected $length_max;

    /** @var int */
    protected $width_max;

    /** @var int */
    protected $height_max;

    /** @var float */
    protected $volume_max;

    /** @var Price */
    protected $price;

    /** @var Price */
    protected $purchase_price;

    /** @var Price */
    protected $fuel_surcharge;

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
        $this->weight_min = $weightMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeightMin(): ?int
    {
        return $this->weight_min;
    }

    /**
     * @param int|null $weightMax
     * @return $this
     */
    public function setWeightMax(?int $weightMax): self
    {
        $this->weight_max = $weightMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeightMax(): ?int
    {
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
     * @param Price|null $price
     * @return $this
     */
    public function setFuelSurcharge(?Price $price): self
    {
        $this->fuel_surcharge = $price;

        return $this;
    }

    /**
     * @return Price|null
     */
    public function getFuelSurcharge(): ?Price
    {
        return $this->fuel_surcharge;
    }

}
