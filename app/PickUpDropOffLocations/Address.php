<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

class Address
{
    /** @var string */
    protected $street1;
    /** @var string */
    protected $street2;
    /** @var int */
    protected $streetNumber;
    /** @var string */
    protected $streetNumberSuffix;
    /** @var string */
    protected $countryCode;
    /** @var string */
    protected $person;
    /** @var string */
    protected $city;
    /** @var string */
    protected $postalCode;
    /** @var string */
    protected $phoneNumber;
    /** @var string */
    protected $regionCode;
    /** @var string */
    protected $email;
    /** @var string */
    protected $company;

    /**
     * @return string|null
     */
    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    /**
     * @param string $street1
     * @return $this
     */
    public function setStreet1(string $street1): self
    {
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
     * @param string $street2
     * @return $this
     */
    public function setStreet2(string $street2): self
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
     * @param int $streetNumber
     * @return Address
     */
    public function setStreetNumber(int $streetNumber): self
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
     * @param string $streetNumberSuffix
     * @return $this
     */
    public function setStreetNumberSuffix(string $streetNumberSuffix): self
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
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode(string $postalCode): self
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
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
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
     * @param string $regionCode
     * @return $this
     */
    public function setRegionCode(string $regionCode): self
    {
        $this->regionCode = $regionCode;

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
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerson(): ?string
    {
        return $this->person;
    }

    /**
     * @param string $person
     * @return $this
     */
    public function setPerson(string $person): self
    {
        $this->person = $person;

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
     * @param string $company
     * @return $this
     */
    public function setCompany(string $company): self
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
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
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
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
