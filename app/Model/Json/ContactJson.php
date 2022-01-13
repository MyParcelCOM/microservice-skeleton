<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Model\Json;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;

class ContactJson extends DataTransferObject
{
    public ?string $company;

    #[MapFrom('first_name')]
    #[MapTo('first_name')]
    public ?string $firstName;

    #[MapFrom('last_name')]
    #[MapTo('last_name')]
    public ?string $lastName;

    public ?string $email;

    #[MapFrom('phone_number')]
    #[MapTo('phone_number')]
    public ?string $phoneNumber;

    public function getName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }
}
