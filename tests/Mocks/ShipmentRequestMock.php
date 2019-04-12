<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use MyParcelCom\Hermes\Http\ShipmentRequest;

class ShipmentRequestMock extends ShipmentRequest
{
    /**
     * @inheritDoc
     */
    protected function carrierSpecificShipmentRules(): array
    {
        return [
            'data.attributes.recipient_address.email'        => 'required_with:data.attributes.customs|email',
            'data.attributes.description'                    => 'required|max:15',
            'data.attributes.recipient_address.phone_number' => 'required_without:data.attributes.recipient_address.email|between:8,15|regex:/^\+?[0-9-]*$/',
            'data.attributes.service.code'                   => 'required|in:service-a,service-b,service-c',
            'data.attributes.sender_address.phone_number'    => 'required_if:data.attributes.recipient_address.country_code,CI,data.attributes.recipient_address.region_code,ENG',
            'data.attributes.items.*.hs_code'                => 'required',
            'data.attributes.items.*.item_weight'            => 'min:1000|integer',
        ];
    }
}
