<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use Illuminate\Support\Arr;
use VIISON\AddressSplitter\AddressSplitter;
use VIISON\AddressSplitter\Exceptions\SplittingException;

class Address
{
    protected ?string $street1 = null;
    protected ?string $street2 = null;
    protected ?int $streetNumber = null;
    protected ?string $streetNumberSuffix = null;
    protected ?string $postalCode = null;
    protected ?string $city = null;
    protected ?string $regionCode = null;
    protected ?string $stateCode = null;
    protected ?string $countryCode = null;
    protected ?string $firstName = null;
    protected ?string $lastName = null;
    protected ?string $company = null;
    protected ?string $email = null;
    protected ?string $phoneNumber = null;

    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    public function setStreet1(?string $street1, bool $combined = false): self
    {
        if ($street1 && $combined) {
            try {
                $addressBreakdown = AddressSplitter::splitAddress($street1);
                $street1 = Arr::get($addressBreakdown, 'streetName');
                $number = Arr::get($addressBreakdown, 'houseNumberParts.base');
                $this->setStreetNumber(empty($number) ? null : (int) $number);
                $this->setStreetNumberSuffix(Arr::get($addressBreakdown, 'houseNumberParts.extension'));
                $this->setStreet2(Arr::get($addressBreakdown, 'additionToAddress2'));
            } catch (SplittingException $e) {
                // cannot split address, ignore
            }
        }

        $this->street1 = $street1;

        return $this;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function setStreet2(?string $street2): self
    {
        $this->street2 = $street2;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetNumberSuffix(): ?string
    {
        return $this->streetNumberSuffix;
    }

    public function setStreetNumberSuffix(?string $streetNumberSuffix): self
    {
        $this->streetNumberSuffix = $streetNumberSuffix;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * @deprecated
     */
    public function setRegionCode(?string $regionCode): self
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    public function getStateCode(): ?string
    {
        return $this->stateCode;
    }

    public function setStateCode(?string $stateCode): self
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
