<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use DateTime;

class OpeningHour
{
    /** @var string */
    protected $day;

    /** @var DateTime */
    protected $open;

    /** @var DateTime */
    protected $closed;

    /**
     * @return string|null
     */
    public function getDay(): ?string
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return $this
     */
    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getOpen(): ?DateTime
    {
        return $this->open;
    }

    /**
     * @param DateTime $open
     * @return $this
     */
    public function setOpen(DateTime $open): self
    {
        $this->open = $open;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getClosed(): ?DateTime
    {
        return $this->closed;
    }

    /**
     * @param DateTime $closed
     * @return $this
     */
    public function setClosed(DateTime $closed): self
    {
        $this->closed = $closed;

        return $this;
    }
}
