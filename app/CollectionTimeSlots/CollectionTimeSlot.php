<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

class CollectionTimeSlot
{
    protected string $id;
    protected string $from;
    protected string $to;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CollectionTimeSlot
     */
    public function setId(string $id): CollectionTimeSlot
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return CollectionTimeSlot
     */
    public function setFrom(string $from): CollectionTimeSlot
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return CollectionTimeSlot
     */
    public function setTo(string $to): CollectionTimeSlot
    {
        $this->to = $to;

        return $this;
    }

}
