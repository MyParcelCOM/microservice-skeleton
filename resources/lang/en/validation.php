<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'                  => 'The :attribute must be accepted.',
    'active_url'                => 'The :attribute is not a valid URL.',
    'after'                     => 'The :attribute must be a date after :date.',
    'after_or_equal'            => 'The :attribute must be a date after or equal to :date.',
    'alpha'                     => 'The :attribute may only contain letters.',
    'alpha_dash'                => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'                 => 'The :attribute may only contain letters and numbers.',
    'array'                     => 'The :attribute must be an array.',
    'before'                    => 'The :attribute must be a date before :date.',
    'before_or_equal'           => 'The :attribute must be a date before or equal to :date.',
    'between'                   => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'                   => 'The :attribute field must be true or false.',
    'combined_fields_max'       => 'Together with :others, :attribute cannot exceed :max characters (including spaces in between).',
    'confirmed'                 => 'The :attribute confirmation does not match.',
    'date'                      => 'The :attribute is not a valid date.',
    'date_format'               => 'The :attribute does not match the format :format.',
    'different'                 => 'The :attribute and :other must be different.',
    'digits'                    => 'The :attribute must be :digits digits.',
    'digits_between'            => 'The :attribute must be between :min and :max digits.',
    'dimensions'                => 'The :attribute has invalid image dimensions.',
    'distinct'                  => 'The :attribute field has a duplicate value.',
    'email'                     => 'The :attribute is not formatted as a valid email address.',
    'exists'                    => 'The selected :attribute is invalid.',
    'file'                      => 'The :attribute must be a file.',
    'filled'                    => 'The :attribute field must have a value.',
    'image'                     => 'The :attribute must be an image.',
    'in'                        => 'The :attribute must be one of the following: :values.',
    'in_array'                  => 'The :attribute field does not exist in :other.',
    'integer'                   => 'The :attribute must be an integer.',
    'ip'                        => 'The :attribute must be a valid IP address.',
    'ipv4'                      => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                      => 'The :attribute must be a valid IPv6 address.',
    'json'                      => 'The :attribute must be a valid JSON string.',
    'max'                       => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                     => 'The :attribute must be a file of type: :values.',
    'mimetypes'                 => 'The :attribute must be a file of type: :values.',
    'min'                       => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'                    => 'The selected :attribute is invalid.',
    'numeric'                   => 'The :attribute must be a number.',
    'present'                   => 'The :attribute must be present.',
    'regex'                     => 'The :attribute is incorrectly formatted.',
    'required'                  => 'The :attribute is required.',
    'required_if'               => 'The :attribute is required when :other is :value.',
    'required_if_international' => 'The :attribute is required for international shipments.',
    'required_unless'           => 'The :attribute is required unless :other is in :values.',
    'required_with'             => 'The :attribute is required when :values is present.',
    'required_with_all'         => 'The :attribute is required when :values is present.',
    'required_without'          => 'The :attribute is required when :values is not present.',
    'required_without_all'      => 'The :attribute is required when none of :values are present.',
    'same'                      => 'The :attribute and :other must match.',
    'size'                      => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'                    => 'The :attribute must be a string.',
    'timezone'                  => 'The :attribute must be a valid timezone.',
    'unique'                    => 'The :attribute has already been taken.',
    'uploaded'                  => 'The :attribute failed to upload.',
    'url'                       => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'data.attributes.recipient_address.street_1'             => 'recipient address street 1',
        'data.attributes.recipient_address.street_2'             => 'recipient address street 2',
        'data.attributes.recipient_address.street_number'        => 'recipient address street number',
        'data.attributes.recipient_address.street_number_suffix' => 'recipient address street number suffix',
        'data.attributes.recipient_address.postal_code'          => 'recipient address postal code',
        'data.attributes.recipient_address.city'                 => 'recipient address city',
        'data.attributes.recipient_address.region_code'          => 'recipient address region code',
        'data.attributes.recipient_address.country_code'         => 'recipient address country code',
        'data.attributes.recipient_address.first_name'           => 'recipient first name',
        'data.attributes.recipient_address.last_name'            => 'recipient last name',
        'data.attributes.recipient_address.company'              => 'recipient address company',
        'data.attributes.recipient_address.email'                => 'recipient email address',
        'data.attributes.recipient_address.phone_number'         => 'recipient phone number',

        'data.attributes.sender_address.street_1'             => 'sender address street 1',
        'data.attributes.sender_address.street_2'             => 'sender address street 2',
        'data.attributes.sender_address.street_number'        => 'sender address street number',
        'data.attributes.sender_address.street_number_suffix' => 'sender address street number suffix',
        'data.attributes.sender_address.postal_code'          => 'sender address postal code',
        'data.attributes.sender_address.city'                 => 'sender address city',
        'data.attributes.sender_address.region_code'          => 'sender address region code',
        'data.attributes.sender_address.country_code'         => 'sender address country code',
        'data.attributes.sender_address.first_name'           => 'sender first name',
        'data.attributes.sender_address.last_name'            => 'sender last name',
        'data.attributes.sender_address.company'              => 'sender address company',
        'data.attributes.sender_address.email'                => 'sender email address',
        'data.attributes.sender_address.phone_number'         => 'sender phone number',

        'data.attributes.return_address.street_1'             => 'return address street 1',
        'data.attributes.return_address.street_2'             => 'return address street 2',
        'data.attributes.return_address.street_number'        => 'return address street number',
        'data.attributes.return_address.street_number_suffix' => 'return address street number suffix',
        'data.attributes.return_address.postal_code'          => 'return address postal code',
        'data.attributes.return_address.city'                 => 'return address city',
        'data.attributes.return_address.region_code'          => 'return address region code',
        'data.attributes.return_address.country_code'         => 'return address country code',
        'data.attributes.return_address.first_name'           => 'return address first name',
        'data.attributes.return_address.last_name'            => 'return address last name',
        'data.attributes.return_address.company'              => 'return address company',
        'data.attributes.return_address.email'                => 'return address email address',
        'data.attributes.return_address.phone_number'         => 'return address phone number',

        'data.attributes.pickup_location'                              => 'pickup location',
        'data.attributes.pickup_location.code'                         => 'pickup location code',
        'data.attributes.pickup_location.address.street_1'             => 'pickup location address street 1',
        'data.attributes.pickup_location.address.street_2'             => 'pickup location address street 2',
        'data.attributes.pickup_location.address.street_number'        => 'pickup location address street number',
        'data.attributes.pickup_location.address.street_number_suffix' => 'pickup location address street number suffix',
        'data.attributes.pickup_location.address.postal_code'          => 'pickup location address postal code',
        'data.attributes.pickup_location.address.city'                 => 'pickup location address city',
        'data.attributes.pickup_location.address.region_code'          => 'pickup location address region code',
        'data.attributes.pickup_location.address.country_code'         => 'pickup location address country code',
        'data.attributes.pickup_location.address.first_name'           => 'pickup location address first name',
        'data.attributes.pickup_location.address.last_name'            => 'pickup location address last name',
        'data.attributes.pickup_location.address.company'              => 'pickup location address company',
        'data.attributes.pickup_location.address.email'                => 'pickup location address email address',
        'data.attributes.pickup_location.address.phone_number'         => 'pickup location address phone number',

        'data.attributes.recipient_tax_number'                      => 'recipient tax number',
        'data.attributes.sender_tax_number'                         => 'sender tax number',
        'data.attributes.tax_identification_numbers.*.country_code' => 'tax identification number country code',
        'data.attributes.tax_identification_numbers.*.number'       => 'tax identification number',
        'data.attributes.tax_identification_numbers.*.description'  => 'tax identification number description',
        'data.attributes.tax_identification_numbers.*.type'         => 'tax identification number type',

        'data.attributes.description' => 'shipment description',

        'data.attributes.service.code' => 'service code',
        'data.attributes.service.name' => 'service name',

        'data.attributes.options.*.code' => 'service option code',
        'data.attributes.options.*.name' => 'service option name',

        'data.attributes.total_value.amount'   => 'total value amount',
        'data.attributes.total_value.currency' => 'total value currency',

        'data.attributes.physical_properties.height'            => 'specified parcel height',
        'data.attributes.physical_properties.width'             => 'specified parcel width',
        'data.attributes.physical_properties.length'            => 'specified parcel length',
        'data.attributes.physical_properties.volume'            => 'specified parcel volume',
        'data.attributes.physical_properties.weight'            => 'specified parcel weight',
        'data.attributes.physical_properties.volumetric_weight' => 'specified parcel volumetric weight',

        'data.attributes.items.*.sku'                 => 'shipment item sku code',
        'data.attributes.items.*.description'         => 'shipment item description',
        'data.attributes.items.*.image_url'           => 'shipment item image URL',
        'data.attributes.items.*.item_value'          => 'shipment item value',
        'data.attributes.items.*.item_value.amount'   => 'shipment item value amount',
        'data.attributes.items.*.item_value.currency' => 'shipment item value currency',
        'data.attributes.items.*.item_weight'         => 'shipment item weight',
        'data.attributes.items.*.quantity'            => 'shipment item quantity',
        'data.attributes.items.*.hs_code'             => 'shipment item HS code',
        'data.attributes.items.*.origin_country_code' => 'shipment item origin country code',
        'data.attributes.items.*.vat_percentage'      => 'shipment item vat percentage',

        'data.attributes.customs'                         => 'customs',
        'data.attributes.customs.content_type'            => 'customs content_type',
        'data.attributes.customs.invoice number'          => 'customs invoice number',
        'data.attributes.customs.non_delivery'            => 'customs non delivery',
        'data.attributes.customs.incoterm'                => 'customs incoterm',
        'data.attributes.customs.shipping_value.amount'   => 'customs shipping value amount',
        'data.attributes.customs.shipping_value.currency' => 'customs shipping value currency',

        'data.attributes.channel' => 'channel',
    ],

];
