<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class ShipmentItem
{
    /** @var string|null */
    protected $sku;

    /** @var string */
    protected $description;

    /** @var string|null */
    protected $imageUrl;

    /** @var string|null */
    protected $hsCode;

    /** @var int */
    protected $quantity;

    /** @var int|null */
    protected $itemValueAmount;

    /** @var string|null */
    protected $itemValueCurrency;

    /** @var string|null */
    protected $originCountryCode;

    /** @var int|null */
    protected $itemWeight;

    /** @var int|null */
    protected $itemTaxAmount;

    /** @var string|null */
    protected $itemTaxCurrency;

    /** @var int|null */
    protected $itemDutyAmount;

    /** @var string|null */
    protected $itemDutyCurrency;

    /**
     * @param string|null $sku
     * @return $this
     */
    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string|null $imageUrl
     * @return $this
     */
    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $hsCode
     * @return $this
     */
    public function setHsCode(?string $hsCode): self
    {
        $this->hsCode = $hsCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHsCode(): ?string
    {
        return $this->hsCode;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $amount
     * @return $this
     */
    public function setItemValueAmount(?int $amount): self
    {
        $this->itemValueAmount = $amount;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemValueAmount(): ?int
    {
        return $this->itemValueAmount;
    }

    /**
     * @param string|null $currency
     * @return $this
     */
    public function setItemValueCurrency(?string $currency): self
    {
        $this->itemValueCurrency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemValueCurrency(): ?string
    {
        return $this->itemValueCurrency;
    }

    /**
     * @param string|null $countryCode
     * @return $this
     */
    public function setOriginCountryCode(?string $countryCode): self
    {
        $this->originCountryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginCountryCode(): ?string
    {
        return $this->originCountryCode;
    }

    /**
     * @return int|null
     */
    public function getItemWeight(): ?int
    {
        return $this->itemWeight;
    }

    /**
     * @param int|null $itemWeight
     * @return $this
     */
    public function setItemWeight(?int $itemWeight): self
    {
        $this->itemWeight = $itemWeight;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemTaxAmount(): ?int
    {
        return $this->itemTaxAmount;
    }

    /**
     * @param int|null $itemTaxAmount
     * @return $this
     */
    public function setItemTaxAmount(?int $itemTaxAmount): self
    {
        $this->itemTaxAmount = $itemTaxAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemTaxCurrency(): ?string
    {
        return $this->itemTaxCurrency;
    }

    /**
     * @param string|null $itemTaxCurrency
     * @return $this
     */
    public function setItemTaxCurrency(?string $itemTaxCurrency): self
    {
        $this->itemTaxCurrency = $itemTaxCurrency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemDutyAmount(): ?int
    {
        return $this->itemDutyAmount;
    }

    /**
     * @param int|null $itemDutyAmount
     * @return $this
     */
    public function setItemDutyAmount(?int $itemDutyAmount): self
    {
        $this->itemDutyAmount = $itemDutyAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemDutyCurrency(): ?string
    {
        return $this->itemDutyCurrency;
    }

    /**
     * @param string|null $itemDutyCurrency
     * @return $this
     */
    public function setItemDutyCurrency(?string $itemDutyCurrency): self
    {
        $this->itemDutyCurrency = $itemDutyCurrency;

        return $this;
    }
}
