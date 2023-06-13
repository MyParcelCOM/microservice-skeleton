<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses\Publish;

class Recalculate implements PostponePoll
{
    public function serialize(): false
    {
        return false;
    }
}
