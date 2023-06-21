<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

class PickUpDropOffLocation
{
    const CATEGORY_PICK_UP = 'pick-up';
    const CATEGORY_DROP_OFF = 'drop-off';
    const FEATURE_PRINT_LABEL_IN_STORE = 'print-label-in-store';

    protected ?string $id = null;
    protected array $categories = [];
    protected array $features = [];
    protected ?Address $address = null;
    protected array $openingHours = [];
    protected ?Position $position = null;
    protected ?int $distance = null;
    protected ?string $locationType = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getFeatures(): ?array
    {
        return $this->features;
    }

    public function setFeatures(array $features): self
    {
        $this->features = $features;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

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

    public function setOpeningHours(OpeningHour ...$openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getLocationType(): ?string
    {
        return $this->locationType;
    }

    public function setLocationType(?string $locationType): self
    {
        $this->locationType = $locationType;

        return $this;
    }
}
