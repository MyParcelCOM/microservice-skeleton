<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\Microservice\PickUpDropOffLocations\Address;

class Shipment
{
    public const LABEL_MIME_TYPE_PDF = 'application/pdf';
    public const LABEL_MIME_TYPE_ZPL = 'application/zpl';

    /** @var string */
    protected string $id;

    /** @var Address */
    protected Address $recipientAddress;

    /** @var array */
    protected array $recipientTaxIdentificationNumbers;

    /** @var string|null */
    protected ?string $recipientTaxNumber;

    /** @var Address */
    protected Address $senderAddress;

    /** @var array */
    protected array $senderTaxIdentificationNumbers;

    /** @var string|null */
    protected ?string $senderTaxNumber;

    /** @var Address */
    protected Address $returnAddress;

    /** @var string|null */
    protected ?string $pickupLocationCode;

    /** @var Address|null */
    protected ?Address $pickupLocationAddress;

    /** @var string|null */
    protected ?string $description;

    /** @var int|null */
    protected ?int $totalValueAmount;

    /** @var string|null */
    protected ?string $totalValueCurrency;

    /** @var string|null */
    protected ?string $trackingCode;

    /** @var string|null */
    protected ?string $trackingUrl;

    /** @var string|null */
    protected ?string $barcode;

    /** @var Service */
    protected Service $service;

    /** @var Option[] */
    protected array $options = [];

    /** @var PhysicalProperties */
    protected PhysicalProperties $physicalProperties;

    /** @var File[] */
    protected array $files = [];

    /** @var Customs|null */
    protected ?Customs $customs;

    /** @var ShipmentItem[] */
    protected array $items = [];

    /** @var bool */
    protected bool $trackTraceEnabled = true;

    /** @var string */
    protected string $myparcelcomShipmentId;

    /** @var string */
    protected string $labelMimeType = self::LABEL_MIME_TYPE_PDF;

    /** @var string|null */
    protected ?string $channel;

    /** @var array */
    protected array $taxIdentificationNumbers = [];

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
     * @return string|null
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
     * @param string|null $channel
     * @return $this
     */
    public function setChannel(?string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @return string|null
     */
    public function getRecipientTaxNumber(): ?string
    {
        return $this->recipientTaxNumber;
    }

    /**
     * @param string $recipientTaxNumber
     * @return $this
     */
    public function setRecipientTaxNumber(string $recipientTaxNumber): self
    {
        $this->recipientTaxNumber = $recipientTaxNumber;

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipientTaxIdentificationNumbers(): array
    {
        return $this->recipientTaxIdentificationNumbers;
    }

    /**
     * @param array $recipientTaxIdentificationNumbers
     * @return $this
     */
    public function setRecipientTaxIdentificationNumbers(array $recipientTaxIdentificationNumbers): self
    {
        $this->recipientTaxIdentificationNumbers = $recipientTaxIdentificationNumbers;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSenderTaxNumber(): ?string
    {
        return $this->senderTaxNumber;
    }

    /**
     * @param string $senderTaxNumber
     * @return $this
     */
    public function setSenderTaxNumber(string $senderTaxNumber): self
    {
        $this->senderTaxNumber = $senderTaxNumber;

        return $this;
    }

    /**
     * @return array
     */
    public function getSenderTaxIdentificationNumbers(): array
    {
        return $this->senderTaxIdentificationNumbers;
    }

    /**
     * @param array $senderTaxIdentificationNumbers
     * @return $this
     */
    public function setSenderTaxIdentificationNumbers(array $senderTaxIdentificationNumbers): self
    {
        $this->senderTaxIdentificationNumbers = $senderTaxIdentificationNumbers;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalValueAmount(): ?int
    {
        return $this->totalValueAmount;
    }

    /**
     * @param int|null $totalValueAmount
     * @return $this
     */
    public function setTotalValueAmount(?int $totalValueAmount): self
    {
        $this->totalValueAmount = $totalValueAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTotalValueCurrency(): ?string
    {
        return $this->totalValueCurrency;
    }

    /**
     * @param string|null $totalValueCurrency
     * @return $this
     */
    public function setTotalValueCurrency(?string $totalValueCurrency): self
    {
        $this->totalValueCurrency = $totalValueCurrency;

        return $this;
    }

    /**
     * Total value based on attributes.total_value with fallback to a sum of all attributes.items.*.item_value
     * @return array|null
     */
    public function getTotalValue(): ?array
    {
        if ($this->getTotalValueAmount()) {
            return [
                'amount'   => $this->getTotalValueAmount(),
                'currency' => $this->getTotalValueCurrency(),
            ];
        }

        $totalItemValue = 0;
        $totalItemCurrency = '';

        foreach ($this->getItems() as $item) {
            if ($item->getItemValueAmount()) {
                $totalItemValue += $item->getItemValueAmount() * $item->getQuantity();
                $totalItemCurrency = $item->getItemValueCurrency();
            }
        }

        if ($totalItemValue) {
            return [
                'amount'   => $totalItemValue,
                'currency' => $totalItemCurrency,
            ];
        }

        return null;
    }

    /**
     * @param array $taxIdentificationNumbers
     * @return $this
     */
    public function setTaxIdentificationNumbers(array $taxIdentificationNumbers): self
    {
        $this->taxIdentificationNumbers = $taxIdentificationNumbers;

        return $this;
    }

    /**
     * @return array
     */
    public function getTaxIdentificationNumbers(): array
    {
        return $this->taxIdentificationNumbers;
    }

    /**
     * @param string $type
     * @param string $countryCode
     * @return string|null
     */
    public function getTaxIdentificationNumber(string $type, string $countryCode): ?string
    {
        foreach ($this->taxIdentificationNumbers as $number) {
            if ($number['type'] === $type && $number['country_code'] === $countryCode) {
                return $number['number'];
            }
        }

        return null;
    }
}
