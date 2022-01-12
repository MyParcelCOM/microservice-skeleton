<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Model\Json;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;

class AddressJson extends DataTransferObject
{
    #[MapFrom('street_1')]
    #[MapTo('street_1')]
    public ?string $street1;

    #[MapFrom('street_2')]
    #[MapTo('street_2')]
    public ?string $street2;

    #[MapFrom('street_number')]
    #[MapTo('street_number')]
    public ?int $streetNumber;

    #[MapFrom('street_number_suffix')]
    #[MapTo('street_number_suffix')]
    public ?string $streetNumberSuffix;

    #[MapFrom('postal_code')]
    #[MapTo('postal_code')]
    public ?string $postalCode;

    public ?string $city;

    #[MapFrom('state_code')]
    #[MapTo('state_code')]
    public ?string $stateCode;

    #[MapFrom('country_code')]
    #[MapTo('country_code')]
    public ?string $countryCode;
}
