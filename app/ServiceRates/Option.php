<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use MyParcelCom\Microservice\Shipments\Option as ShipmentOption;

class Option extends ShipmentOption
{
    private ?Price $price = null;

    public static function fromShipmentOption(ShipmentOption $shipmentOption): self
    {
        return (new self())
            ->setName($shipmentOption->getName())
            ->setCode($shipmentOption->getCode())
            ->setValues($shipmentOption->getValues());
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(?Price $price): self
    {
        $this->price = $price;

        return $this;
    }
}
