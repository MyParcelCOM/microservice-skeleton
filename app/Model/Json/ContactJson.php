<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Model\Json;

class ContactJson extends DataTransferObject
{
    // TODO: update package and make properties private once we have updated to PHP 8.
    public ?string $company;
    public ?string $first_name;
    public ?string $last_name;
    public ?string $email;
    public ?string $phone_number;

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->first_name = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $lastName): self
    {
        $this->last_name = $lastName;

        return $this;
    }

    public function getName(): string
    {
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email ? trim(strtolower($email)) : null;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }
}
