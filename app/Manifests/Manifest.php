<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Manifest
{

    /**
     * @param string $name
     */
    public function __construct(private string $name)
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
