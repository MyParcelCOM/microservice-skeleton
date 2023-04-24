<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

use Carbon\Carbon;

class CollectionTimeSlot
{
    public function __construct(
        private string $id,
        private Carbon $from,
        private Carbon $to,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFrom(): Carbon
    {
        return $this->from;
    }

    public function setFrom(Carbon $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): Carbon
    {
        return $this->to;
    }

    public function setTo(Carbon $to): self
    {
        $this->to = $to;

        return $this;
    }
}
