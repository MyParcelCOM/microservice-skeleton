<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as IlluminateEventServiceProvider;
use MyParcelCom\Microservice\Subscribers\CarrierSpanSubscriber;
use MyParcelCom\Microservice\Subscribers\HttpRequestSpanSubscriber;

class EventServiceProvider extends IlluminateEventServiceProvider
{
    protected $subscribe = [
        HttpRequestSpanSubscriber::class,
        CarrierSpanSubscriber::class,
    ];
}
