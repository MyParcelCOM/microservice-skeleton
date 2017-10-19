<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

class PickUpDropOffLocation
{
    /** @var string */
    protected $id;

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
