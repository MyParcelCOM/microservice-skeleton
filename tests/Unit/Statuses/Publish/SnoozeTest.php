<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses\Publish;

use DateInterval;
use MyParcelCom\Microservice\Statuses\Publish\Snooze;
use PHPUnit\Framework\TestCase;

class SnoozeTest extends TestCase
{
    public function testItConvertsDateIntervalToStringContainingHoursMinutesAndSeconds(): void
    {
        $snooze = new Snooze(new DateInterval('PT1H2M3S'));

        $this->assertEquals('PT1H2M3S', $snooze->serialize());
    }
}
