<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use DateTime;

class OpeningHour
{
    /** @var string|null */
    protected $day;

    /** @var DateTime|null */
    protected $open;

    /** @var DateTime|null */
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
     * @param DateTime|null $open
     * @return $this
     */
    public function setOpen(?DateTime $open): self
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
     * @param DateTime|null $closed
     * @return $this
     */
    public function setClosed(?DateTime $closed): self
    {
        $this->closed = $closed;

        return $this;
    }
}
