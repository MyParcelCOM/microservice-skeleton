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

    private ?string $contentType = null;
    private ?string $invoiceNumber = null;
    private ?string $nonDelivery = null;
    private ?string $incoterm = null;
    private ?int $shippingValueAmount = null;
    private ?string $shippingValueCurrency = null;
    private ?int $totalTaxAmount = null;
    private ?string $totalTaxCurrency = null;
    private ?int $totalDutyAmount = null;
    private ?string $totalDutyCurrency = null;

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getNonDelivery(): ?string
    {
        return $this->nonDelivery;
    }

    public function setNonDelivery(?string $nonDelivery): self
    {
        $this->nonDelivery = $nonDelivery;

        return $this;
    }

    public function getIncoterm(): ?string
    {
        return $this->incoterm;
    }

    public function setIncoterm(?string $incoterm): self
    {
        $this->incoterm = $incoterm;

        return $this;
    }

    public function getShippingValueAmount(): ?int
    {
        return $this->shippingValueAmount;
    }

    public function setShippingValueAmount(?int $shippingValueAmount): self
    {
        $this->shippingValueAmount = $shippingValueAmount;

        return $this;
    }

    public function getShippingValueCurrency(): ?string
    {
        return $this->shippingValueCurrency;
    }

    public function setShippingValueCurrency(?string $shippingValueCurrency): self
    {
        $this->shippingValueCurrency = $shippingValueCurrency;

        return $this;
    }

    public function getTotalTaxAmount(): ?int
    {
        return $this->totalTaxAmount;
    }

    public function setTotalTaxAmount(?int $totalTaxAmount): self
    {
        $this->totalTaxAmount = $totalTaxAmount;

        return $this;
    }

    public function getTotalTaxCurrency(): ?string
    {
        return $this->totalTaxCurrency;
    }

    public function setTotalTaxCurrency(?string $totalTaxCurrency): self
    {
        $this->totalTaxCurrency = $totalTaxCurrency;

        return $this;
    }

    public function getTotalDutyAmount(): ?int
    {
        return $this->totalDutyAmount;
    }

    public function setTotalDutyAmount(?int $totalDutyAmount): self
    {
        $this->totalDutyAmount = $totalDutyAmount;

        return $this;
    }

    public function getTotalDutyCurrency(): ?string
    {
        return $this->totalDutyCurrency;
    }

    public function setTotalDutyCurrency(?string $totalDutyCurrency): self
    {
        $this->totalDutyCurrency = $totalDutyCurrency;

        return $this;
    }
}
