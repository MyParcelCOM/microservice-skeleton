<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Model\Json;

class AddressJson extends DataTransferObject
{
    // TODO: update package and make properties private once we have updated to PHP 8.
    public ?string $street_1;
    public ?string $street_2;
    public ?int $street_number;
    public ?string $street_number_suffix;
    public ?string $postal_code;
    public ?string $city;
    public ?string $state_code;
    public ?string $country_code;

    public function getStreet1(): ?string
    {
        return $this->street_1;
    }

    public function setStreet1(string $street1): self
    {
        $this->street_1 = $street1;

        return $this;
    }

    public function getStreet2(): ?string
    {
        return $this->street_2;
    }

    public function setStreet2(?string $street2): self
    {
        $this->street_2 = $street2;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->street_number;
    }

    public function setStreetNumber(?int $streetNumber): self
    {
        $this->street_number = $streetNumber;

        return $this;
    }

    public function getStreetNumberSuffix(): ?string
    {
        return $this->street_number_suffix;
    }

    public function setStreetNumberSuffix(?string $streetNumberSuffix): self
    {
        $this->street_number_suffix = $streetNumberSuffix;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postal_code = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStateCode(): ?string
    {
        return $this->state_code;
    }

    public function setStateCode(?string $stateCode): self
    {
        $this->state_code = $stateCode;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->country_code = $countryCode;

        return $this;
    }
}
