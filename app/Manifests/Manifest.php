<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MyParcelCom\Microservice\Shipments\File;

class Manifest
{
    /**
     * @param string $name
     * @param File[] $files
     */
    public function __construct(private string $name, private array $files = [])
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * @return Collection
     */
    public function getShipments(): Collection
    {
        // return $this->shipments;
    }

    /**
     * @return HasMany
     */
    public function shipments(): HasMany
    {
        // return $this->hasMany(Shipment::class);
    }
}
