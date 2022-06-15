<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MyParcelCom\Microservice\Enums\TaxTypeEnum;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;

class Shipment
{
    public const LABEL_MIME_TYPE_PDF = 'application/pdf';
    public const LABEL_MIME_TYPE_ZPL = 'application/zpl';
    public const LABEL_SIZE_A6 = 'A6';
    public const LABEL_SIZE_A4 = 'A4';

    /** @var string */
    protected $id;

    /** @var string|null */
    protected $sequenceNumber;

    /** @var Address */
    protected $recipientAddress;

    /** @var string|null */
    protected $recipientTaxNumber;

    /** @var array */
    protected $recipientTaxIdentificationNumbers = [];

    /** @var Address */
    protected $senderAddress;

    /** @var string|null */
    protected $senderTaxNumber;

    /** @var array */
    protected $senderTaxIdentificationNumbers = [];

    /** @var Address */
    protected $returnAddress;

    /** @var string|null */
    protected $pickupLocationCode;

    /** @var Address|null */
    protected $pickupLocationAddress;

    /** @var string|null */
    protected $description;

    /** @var int|null */
    protected $totalValueAmount;

    /** @var string|null */
    protected $totalValueCurrency;

    /** @var string|null */
    protected $trackingCode;

    /** @var string|null */
    protected $trackingUrl;

    /** @var string|null */
    protected $barcode;

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

    /** @var string */
    protected $labelSize = self::LABEL_SIZE_A6;

    /** @var string|null */
    protected $channel;

    /** @var array */
    protected $taxIdentificationNumbers = [];

    /** @var Collection */
    protected $consolidationShipments;

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
     * @return string|null
     */
    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    /**
     * @param string $sequenceNumber
     * @return $this
     */
    public function setSequenceNumber(string $sequenceNumber): self
    {
        $this->sequenceNumber = $sequenceNumber;

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
     * @param string $optionCode
     * @return bool
     */
    public function hasOption(string $optionCode): bool
    {
        return $this->getOption($optionCode) !== null;
    }

    /**
     * @param string $optionCode
     * @return Option|null
     */
    public function getOption(string $optionCode): ?Option
    {
        return collect($this->getOptions())
            ->first(fn(Option $option) => $option->getCode() === $optionCode);
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

    public function setLabelSize(string $labelSize): self
    {
        $this->labelSize = $labelSize;

        return $this;
    }

    public function getLabelSize(): string
    {
        return $this->labelSize;
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
     * @deprecated
     */
    public function getRecipientTaxNumber(): ?string
    {
        return $this->recipientTaxNumber;
    }

    /**
     * @param string $recipientTaxNumber
     * @return $this
     * @deprecated
     */
    public function setRecipientTaxNumber(string $recipientTaxNumber): self
    {
        $this->recipientTaxNumber = $recipientTaxNumber;

        return $this;
    }

    public function getRecipientTaxIdentificationNumbers(): array
    {
        return $this->recipientTaxIdentificationNumbers;
    }

    public function setRecipientTaxIdentificationNumbers(array $recipientTaxIdentificationNumbers): self
    {
        $this->recipientTaxIdentificationNumbers = $recipientTaxIdentificationNumbers;

        return $this;
    }

    public function getRecipientTaxIdentificationNumber(TaxTypeEnum $type, string ...$countryCodes): ?string
    {
        foreach ($this->getRecipientTaxIdentificationNumbers() as $taxIdentificationNumber) {
            if ($taxIdentificationNumber['type'] === $type->getValue()) {
                if (empty($countryCodes) || in_array($taxIdentificationNumber['country_code'], $countryCodes)) {
                    return $taxIdentificationNumber['number'];
                }
            }
        }

        // Check tax_identification_numbers (can be removed in the future)
        foreach ($this->getTaxIdentificationNumbers() as $taxIdentificationNumber) {
            if ($taxIdentificationNumber['type'] === $type->getValue()) {
                if (empty($countryCodes) || in_array($taxIdentificationNumber['country_code'], $countryCodes)) {
                    return $taxIdentificationNumber['number'];
                }
            }
        }

        // Check recipient_tax_number, but only for previously supported EORI or VAT (can be removed in the future)
        if (!empty($this->getRecipientTaxNumber()) && $type->getValue() !== TaxTypeEnum::IOSS) {
            return $this->getRecipientTaxNumber();
        }

        return null;
    }

    /**
     * @return string|null
     * @deprecated
     */
    public function getSenderTaxNumber(): ?string
    {
        return $this->senderTaxNumber;
    }

    /**
     * @param string $senderTaxNumber
     * @return $this
     * @deprecated
     */
    public function setSenderTaxNumber(string $senderTaxNumber): self
    {
        $this->senderTaxNumber = $senderTaxNumber;

        return $this;
    }

    public function getSenderTaxIdentificationNumbers(): array
    {
        return $this->senderTaxIdentificationNumbers;
    }

    public function setSenderTaxIdentificationNumbers(array $senderTaxIdentificationNumbers): self
    {
        $this->senderTaxIdentificationNumbers = $senderTaxIdentificationNumbers;

        return $this;
    }

    public function getSenderTaxIdentificationNumber(TaxTypeEnum $type, string ...$countryCodes): ?string
    {
        foreach ($this->getSenderTaxIdentificationNumbers() as $taxIdentificationNumber) {
            if ($taxIdentificationNumber['type'] === $type->getValue()) {
                if (empty($countryCodes) || in_array($taxIdentificationNumber['country_code'], $countryCodes)) {
                    return $taxIdentificationNumber['number'];
                }
            }
        }

        // Check tax_identification_numbers (can be removed in the future)
        foreach ($this->getTaxIdentificationNumbers() as $taxIdentificationNumber) {
            if ($taxIdentificationNumber['type'] === $type->getValue()) {
                if (empty($countryCodes) || in_array($taxIdentificationNumber['country_code'], $countryCodes)) {
                    return $taxIdentificationNumber['number'];
                }
            }
        }

        // Check sender_tax_number, but only for previously supported EORI or VAT (can be removed in the future)
        if (!empty($this->getSenderTaxNumber()) && $type->getValue() !== TaxTypeEnum::IOSS) {
            // We only operate in countries where the EORI and VAT start with the country code, so let's filter on that.
            if (empty($countryCodes) || str_contains($this->getSenderTaxNumber(), $countryCodes[0])) {
                return $this->getSenderTaxNumber();
            }
        }

        return null;
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
     *
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
     * @deprecated
     */
    public function setTaxIdentificationNumbers(array $taxIdentificationNumbers): self
    {
        $this->taxIdentificationNumbers = $taxIdentificationNumbers;

        return $this;
    }

    /**
     * @return array
     * @deprecated
     */
    public function getTaxIdentificationNumbers(): array
    {
        return $this->taxIdentificationNumbers;
    }

    /**
     * @param string $type
     * @param string $countryCode
     * @return string|null
     * @deprecated
     */
    public function getTaxIdentificationNumber(string $type, string $countryCode): ?string
    {
        foreach ($this->getTaxIdentificationNumbers() as $number) {
            if ($number['type'] === $type && $number['country_code'] === $countryCode) {
                return $number['number'];
            }
        }

        return null;
    }

    public function setConsolidationShipments(Collection $shipments): self
    {
        $this->consolidationShipments = $shipments;

        return $this;
    }

    public function getConsolidationShipments(): Collection
    {
        return $this->consolidationShipments ?? new Collection();
    }

    /**
     * @param int $colloNumber
     * @return $this
     */
    public function setColloNumber(int $colloNumber): self
    {
        $this->colloNumber = $colloNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getColloNumber(): ?int
    {
        return $this->colloNumber;
    }

//    Todo: Uncomment the below methods when implementing multi colli with a database in the carrier microservice.
//    /**
//     * @return BelongsTo
//     */
//    public function master()
//    {
//        return $this->belongsTo(Shipment::class, 'master_id');
//    }
//
//    /**
//     * @return HasMany
//     */
//    public function colli()
//    {
//        return $this->hasMany(Shipment::class, 'master_id')
//            ->orderBy('collo_number');
//    }

//    TODO: Uncomment the below methods when implementing consolidations with a database in the carrier microservice.
//    public function getConsolidationShipments(): Collection
//    {
//        return $this->consolidationShipments;
//    }
//
//    public function consolidationShipments(): HasMany
//    {
//        return $this->hasMany(Shipment::class, 'consolidation_shipment_id');
//    }

//    TODO: Uncomment the below methods when implementing collections in the carrier microservice.
//    public function collection(): BelongsTo
//    {
//        return $this->belongsTo(Collection::class);
//    }
//
//    public function setCollection(Collection $collection): self
//    {
//        $this->collection()->associate($collection);
//
//        return $this;
//    }
//
//    public function getCollection(): ?Collection
//    {
//        return $this->collection;
//    }
}
