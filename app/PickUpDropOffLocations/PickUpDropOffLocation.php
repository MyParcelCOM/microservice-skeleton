<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

class PickUpDropOffLocation
{
    const CATEGORY_PICK_UP = 'pick-up';
    const CATEGORY_DROP_OFF = 'drop-off';

    /** @var string */
    protected $id;

    /** @var array */
    protected $categories = [];

    /** @var Address */
    protected $address;

    /** @var OpeningHour[] */
    protected $openingHours = [];

    /** @var Position */
    protected $position;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     * @return $this
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return $this
     */
    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return OpeningHour[]
     */
    public function getOpeningHours(): array
    {
        return $this->openingHours;
    }

    /**
     * @param OpeningHour[] $openingHours
     * @return $this
     */
    public function setOpeningHours(OpeningHour ...$openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    /**
     * @return Position|null
     */
    public function getPosition(): ?Position
    {
        return $this->position;
    }

    /**
     * @param Position $position
     * @return $this
     */
    public function setPosition(Position $position): self
    {
        $this->position = $position;

        return $this;
    }
}
