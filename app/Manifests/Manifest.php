<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MyParcelCom\Microservice\Shipments\Shipment;

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
        // Todo: Implement relationships with shipments see the ManifestMapper class.
        // return $this->shipments;
    }

    /**
     * @return HasMany
     */
    public function shipments(): HasMany
    {
        // Todo: Implement relationships with shipments see the ManifestMapper class.
        // return $this->hasMany(Shipment::class);
    }
}
