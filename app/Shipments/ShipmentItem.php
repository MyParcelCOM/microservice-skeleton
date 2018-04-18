<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class ShipmentItem
{
    /** @var string */
    protected $sku;
    /** @var string */
    protected $description;
    /** @var string */
    protected $hsCode;
    /** @var int */
    protected $quantity;
    /** @var int */
    protected $itemValueAmount;
    /** @var string */
    protected $itemValueCurrency;
    /** @var string */
    protected $originCountryCode;

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string
     */
    public function getSku(): string
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
     * @param string $hsCode
     * @return $this
     */
    public function setHsCode(string $hsCode): self
    {
        $this->hsCode = $hsCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getHsCode(): string
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
     * @param int $amount
     * @return $this
     */
    public function setItemValueAmount(int $amount): self
    {
        $this->itemValueAmount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemValueAmount(): int
    {
        return $this->itemValueAmount;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setItemValueCurrency(string $currency): self
    {
        $this->itemValueCurrency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemValueCurrency(): string
    {
        return $this->itemValueCurrency;
    }

    /**
     * @param string $countryCode
     * @return $this
     */
    public function setOriginCountryCode(string $countryCode): self
    {
        $this->originCountryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginCountryCode(): string
    {
        return $this->originCountryCode;
    }
}
