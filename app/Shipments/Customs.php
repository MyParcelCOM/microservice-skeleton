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

    /** @var string */
    protected $contentType;
    /** @var null|string */
    protected $invoiceNumber;
    /** @var string */
    private $nonDelivery;
    /** @var string */
    private $incoterm;

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    /**
     * @param null|string $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber(?string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getNonDelivery(): string
    {
        return $this->nonDelivery;
    }

    /**
     * @param string $nonDelivery
     * @return $this
     */
    public function setNonDelivery(string $nonDelivery): self
    {
        $this->nonDelivery = $nonDelivery;

        return $this;
    }

    /**
     * @return string
     */
    public function getIncoterm(): string
    {
        return $this->incoterm;
    }

    /**
     * @param string $incoterm
     * @return $this
     */
    public function setIncoterm(string $incoterm): self
    {
        $this->incoterm = $incoterm;

        return $this;
    }
}
