<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class Customs
{
    const CONTENT_TYPE_MERCHANDISE = 'merchandise';
    const CONTENT_TYPE_SAMPLE_MERCHANDISE = 'sample_merchandise';
    const CONTENT_TYPE_RETURNED_MERCHANDISE = 'returned_merchandise';
    const CONTENT_TYPE_DOCUMENTS = 'documents';
    const CONTENT_TYPE_GIFTS = 'gifts';

    const NON_DELIVERY_RETURN = 'return';
    const NON_DELIVERY_ABANDON = 'abandon';

    const INCOTERM_DELIVERED_AT_PLACE = 'DAP';
    const INCOTERM_DUTY_DELIVERY_PAID = 'DDP';

    /** @var string|null */
    private $contentType;

    /** @var string|null */
    private $invoiceNumber;

    /** @var string|null */
    private $nonDelivery;

    /** @var string|null */
    private $incoterm;

    /** @var int|null */
    private $shippingValueAmount;

    /** @var string|null */
    private $shippingValueCurrency;

    /** @var int|null */
    private $totalTaxAmount;

    /** @var string|null */
    private $totalTaxCurrency;

    /** @var int|null */
    private $totalDutyAmount;

    /** @var string|null */
    private $totalDutyCurrency;

    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * @param string|null $contentType
     * @return $this
     */
    public function setContentType(?string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string|null $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber(?string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNonDelivery(): ?string
    {
        return $this->nonDelivery;
    }

    /**
     * @param string|null $nonDelivery
     * @return $this
     */
    public function setNonDelivery(?string $nonDelivery): self
    {
        $this->nonDelivery = $nonDelivery;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIncoterm(): ?string
    {
        return $this->incoterm;
    }

    /**
     * @param string|null $incoterm
     * @return $this
     */
    public function setIncoterm(?string $incoterm): self
    {
        $this->incoterm = $incoterm;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getShippingValueAmount(): ?int
    {
        return $this->shippingValueAmount;
    }

    /**
     * @param int|null $shippingValueAmount
     * @return $this
     */
    public function setShippingValueAmount(?int $shippingValueAmount): self
    {
        $this->shippingValueAmount = $shippingValueAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShippingValueCurrency(): ?string
    {
        return $this->shippingValueCurrency;
    }

    /**
     * @param string|null $shippingValueCurrency
     * @return $this
     */
    public function setShippingValueCurrency(?string $shippingValueCurrency): self
    {
        $this->shippingValueCurrency = $shippingValueCurrency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalTaxAmount(): ?int
    {
        return $this->totalTaxAmount;
    }

    /**
     * @param int|null $totalTaxAmount
     * @return $this
     */
    public function setTotalTaxAmount(?int $totalTaxAmount): self
    {
        $this->totalTaxAmount = $totalTaxAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTotalTaxCurrency(): ?string
    {
        return $this->totalTaxCurrency;
    }

    /**
     * @param string|null $totalTaxCurrency
     * @return $this
     */
    public function setTotalTaxCurrency(?string $totalTaxCurrency): self
    {
        $this->totalTaxCurrency = $totalTaxCurrency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalDutyAmount(): ?int
    {
        return $this->totalDutyAmount;
    }

    /**
     * @param int|null $totalDutyAmount
     * @return $this
     */
    public function setTotalDutyAmount(?int $totalDutyAmount): self
    {
        $this->totalDutyAmount = $totalDutyAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTotalDutyCurrency(): ?string
    {
        return $this->totalDutyCurrency;
    }

    /**
     * @param string|null $totalDutyCurrency
     * @return $this
     */
    public function setTotalDutyCurrency(?string $totalDutyCurrency): self
    {
        $this->totalDutyCurrency = $totalDutyCurrency;

        return $this;
    }
}
