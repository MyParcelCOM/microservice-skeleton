<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\Microservice\PickUpDropOffLocations\Address;

class Shipment
{
    public const LABEL_MIME_TYPE_PDF = 'application/pdf';
    public const LABEL_MIME_TYPE_ZPL = 'application/zpl';

    /** @var string */
    protected $id;

    /** @var Address */
    protected $recipientAddress;

    /** @var Address */
    protected $senderAddress;

    /** @var Address */
    protected $returnAddress;

    /** @var string|null */
    protected $pickupLocationCode;

    /** @var Address|null */
    protected $pickupLocationAddress;

    /** @var string|null */
    protected $description;

    /** @var string|null */
    protected $trackingCode;

    /** @var string|null */
    protected $trackingUrl;

    /** @var string|null */
    protected $barcode;

    /** @var int */
    protected $weight;

    /** @var Service */
    protected $service;

    /** @var Option[] */
    protected $options = [];

    /** @var PhysicalProperties */
    protected $physicalProperties;

    /** @var File[] */
    protected $files = [];

    /** @var Customs|null */
    protected $customs;

    /** @var ShipmentItem[] */
    protected $items = [];

    /** @var bool */
    protected $trackTraceEnabled = true;

    /** @var string */
    protected $myparcelcomShipmentId;

    /** @var string */
    protected $labelMimeType = self::LABEL_MIME_TYPE_PDF;

    /** @var null|string */
    protected $channel;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Address
     */
    public function getRecipientAddress(): Address
    {
        return $this->recipientAddress;
    }

    /**
     * @param Address $recipientAddress
     * @return $this
     */
    public function setRecipientAddress(Address $recipientAddress): self
    {
        $this->recipientAddress = $recipientAddress;

        return $this;
    }

    /**
     * @return Address
     */
    public function getReturnAddress(): Address
    {
        return $this->returnAddress;
    }

    /**
     * @param Address $returnAddress
     * @return $this
     */
    public function setReturnAddress(Address $returnAddress): self
    {
        $this->returnAddress = $returnAddress;

        return $this;
    }

    /**
     * @return Address
     */
    public function getSenderAddress(): Address
    {
        return $this->senderAddress;
    }

    /**
     * @param Address $senderAddress
     * @return $this
     */
    public function setSenderAddress(Address $senderAddress): self
    {
        $this->senderAddress = $senderAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupLocationCode(): ?string
    {
        return $this->pickupLocationCode;
    }

    /**
     * @param string|null $pickupLocationCode
     * @return $this
     */
    public function setPickupLocationCode(?string $pickupLocationCode): self
    {
        $this->pickupLocationCode = $pickupLocationCode;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getPickupLocationAddress(): ?Address
    {
        return $this->pickupLocationAddress;
    }

    /**
     * @param Address|null $pickupLocationAddress
     * @return $this
     */
    public function setPickupLocationAddress(?Address $pickupLocationAddress): self
    {
        $this->pickupLocationAddress = $pickupLocationAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string|null $barcode
     * @return $this
     */
    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    /**
     * @param string|null $trackingCode
     * @return $this
     */
    public function setTrackingCode(?string $trackingCode): self
    {
        $this->trackingCode = $trackingCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrackingUrl(): ?string
    {
        return $this->trackingUrl;
    }

    /**
     * @param string|null $trackingUrl
     * @return $this
     */
    public function setTrackingUrl(?string $trackingUrl): self
    {
        $this->trackingUrl = $trackingUrl;

        return $this;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }

    /**
     * @param Service $service
     * @return $this
     */
    public function setService(Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param Option $option
     * @return $this
     */
    public function addOption(Option $option): self
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * @param Option[] $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return PhysicalProperties|null
     */
    public function getPhysicalProperties(): ?PhysicalProperties
    {
        return $this->physicalProperties;
    }

    /**
     * @param PhysicalProperties $physicalProperties
     * @return $this
     */
    public function setPhysicalProperties(PhysicalProperties $physicalProperties): self
    {
        $this->physicalProperties = $physicalProperties;

        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function addFile(File $file): self
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * @param Customs|null $customs
     * @return $this
     */
    public function setCustoms(?Customs $customs): self
    {
        $this->customs = $customs;

        return $this;
    }

    /**
     * @return Customs|null
     */
    public function getCustoms(): ?Customs
    {
        return $this->customs;
    }

    /**
     * @return ShipmentItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param ShipmentItem $item
     * @return $this
     */
    public function addItem(ShipmentItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param ShipmentItem[] $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = [];

        array_walk($items, function (ShipmentItem $item) {
            $this->addItem($item);
        });

        return $this;
    }

    /**
     * @param bool $trackTraceEnabled
     * @return $this
     */
    public function setTrackTraceEnabled(bool $trackTraceEnabled): self
    {
        $this->trackTraceEnabled = $trackTraceEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTrackTraceEnabled(): bool
    {
        return $this->trackTraceEnabled;
    }

    /**
     * @return string
     */
    public function getMyparcelcomShipmentId(): string
    {
        return $this->myparcelcomShipmentId;
    }

    /**
     * @param string $myparcelcomShipmentId
     * @return $this
     */
    public function setMyparcelcomShipmentId(string $myparcelcomShipmentId): self
    {
        $this->myparcelcomShipmentId = $myparcelcomShipmentId;

        return $this;
    }

    /**
     * @param string $labelMimeType
     * @return $this
     */
    public function setLabelMimeType(string $labelMimeType): self
    {
        $this->labelMimeType = $labelMimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelMimeType(): string
    {
        return $this->labelMimeType;
    }

    /**
     * @param null|string $channel
     * @return $this
     */
    public function setChannel(?string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }
}
