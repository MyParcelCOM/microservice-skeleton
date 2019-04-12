<?php

declare(strict_types=1);

namespace MyParcelCom\Hermes\Http;

class ShipmentRequest extends FormRequest
{
    /**
     * Define the validation rules that apply to the request.
     * For example: [
     *   'data.attributes.description                   => 'required|string',
     *   'data.attributes.physical_properties.weight'   => 'integer|min:1000',
     * ]
     * This will enforce the description attribute to be set and to be a string
     * and that the weight of the shipment is an integer and is at least 1000.
     *
     * See the laravel documentation for all available validation rules:
     * https://laravel.com/docs/5.5/validation#available-validation-rules
     *
     * @return array
     */
    protected function carrierSpecificShipmentRules(): array
    {
        return [
            // TODO: Create rules based on carrier specific requirements.
        ];
    }
}
