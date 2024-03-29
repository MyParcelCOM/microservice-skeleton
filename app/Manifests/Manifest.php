<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;
use MyParcelCom\Microservice\Shipments\File;

class Manifest
{
    /** @var array */
    protected $casts = [
        'address_json' => AddressJson::class,
        'contact_json' => ContactJson::class,
    ];

    public function __construct(
        private string $name,
        private AddressJson $address_json,
        private ContactJson $contact_json,
        private array $files = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddressJson(): ?AddressJson
    {
        return $this->address_json;
    }

    public function getContactJson(): ?ContactJson
    {
        return $this->contact_json;
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

    public function getShipments(): Collection
    {
        // return $this->shipments;
    }

    public function shipments(): HasMany
    {
        // return $this->hasMany(Shipment::class);
    }
}
