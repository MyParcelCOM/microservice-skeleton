<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use Illuminate\Support\Arr;
use VIISON\AddressSplitter\AddressSplitter;
use VIISON\AddressSplitter\Exceptions\SplittingException;

class Address
{
    /** @var string|null */
    protected $street1;

    /** @var string|null */
    protected $street2;

    /** @var int|null */
    protected $streetNumber;

    /** @var string|null */
    protected $streetNumberSuffix;

    /** @var string|null */
    protected $postalCode;

    /** @var string|null */
    protected $city;

    /** @var string|null */
    protected $regionCode;

    /** @var string|null */
    protected $stateCode;

    /** @var string|null */
    protected $countryCode;

    /** @var string|null */
    protected $firstName;

    /** @var string|null */
    protected $lastName;

    /** @var string|null */
    protected $company;

    /** @var string|null */
    protected $email;

    /** @var string|null */
    protected $phoneNumber;

    /**
     * @return string|null
     */
    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    /**
     * @param string|null $street1
     * @return $this
     */
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

    /**
     * @return string|null
     */
    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    /**
     * @param string|null $street2
     * @return $this
     */
    public function setStreet2(?string $street2): self
    {
        $this->street2 = $street2;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    /**
     * @param int|null $streetNumber
     * @return $this
     */
    public function setStreetNumber(?int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreetNumberSuffix(): ?string
    {
        return $this->streetNumberSuffix;
    }

    /**
     * @param string|null $streetNumberSuffix
     * @return $this
     */
    public function setStreetNumberSuffix(?string $streetNumberSuffix): self
    {
        $this->streetNumberSuffix = $streetNumberSuffix;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $postalCode
     * @return $this
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * @param string|null $regionCode
     * @return $this
     */
    public function setRegionCode(?string $regionCode): self
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStateCode(): ?string
    {
        return $this->stateCode;
    }

    /**
     * @param string|null $stateCode
     * @return $this
     */
    public function setStateCode(?string $stateCode): self
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string|null $countryCode
     * @return $this
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return $this
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     * @return $this
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
