<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use DateTime;

class OpeningHour
{
    protected ?string $day = null;
    protected ?DateTime $open = null;
    protected ?DateTime $closed = null;

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getOpen(): ?DateTime
    {
        return $this->open;
    }

    public function setOpen(?DateTime $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getClosed(): ?DateTime
    {
        return $this->closed;
    }

    public function setClosed(?DateTime $closed): self
    {
        $this->closed = $closed;

        return $this;
    }
}
