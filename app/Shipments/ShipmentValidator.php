<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class ShipmentValidator
{
    /**
     * @param Shipment $shipment
     * @return string[]
     */
    public function validate(Shipment $shipment): array
    {
        $errors = [];

        // TODO: Check shipment for carrier-specific requirement.
        // TODO: If not present, add a string explaining the error to the $errors array.

        return $errors;
    }
}
