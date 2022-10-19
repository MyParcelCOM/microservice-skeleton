<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

use Carbon\Carbon;

class CollectionTimeSlot
{
    protected string $id;
    protected Carbon $from;
    protected Carbon $to;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getFrom(): Carbon
    {
        return $this->from;
    }

    /**
     * @param Carbon $from
     * @return $this
     */
    public function setFrom(Carbon $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getTo(): Carbon
    {
        return $this->to;
    }

    /**
     * @param Carbon $to
     * @return $this
     */
    public function setTo(Carbon $to): self
    {
        $this->to = $to;

        return $this;
    }
}
