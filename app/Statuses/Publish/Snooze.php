<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses\Publish;

use DateInterval;

class Snooze implements PostponePoll
{
    public function __construct(
        private readonly DateInterval $dateInterval,
    ) {
    }

    public function serialize(): string
    {
        return $this->dateInterval->format('PT%hH%iM%sS');
    }
}
