<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Subscribers;

use Illuminate\Events\Dispatcher;
use MyParcelCom\Microservice\Events\CompletedCarrierApiRequest;
use MyParcelCom\Microservice\Events\ExceptionOccurred;
use MyParcelCom\Microservice\Events\FailedCarrierApiRequest;
use MyParcelCom\Microservice\Events\MakingCarrierApiRequest;
use MyParcelCom\Microservice\Events\SuccessfulCarrierApiRequest;
use OpenTracing\Reference;
use OpenTracing\Scope;
use OpenTracing\Tracer;

class CarrierSpanSubscriber
{
    private static ?Scope $scope = null;

    public function __construct(
        private readonly Tracer $tracer,
    ) {
    }

    public function start(MakingCarrierApiRequest $event): void
    {
        if (!config('jaeger.enabled')) {
            return;
        }
        self::$scope = $this->tracer->startActiveSpan(
            $event->getContext(),
            $this->getSpanOptions(),
        );
        self::$scope->getSpan()->log([
            'url'    => $event->getUrl(),
            'method' => $event->getMethod(),
            'body'   => $event->getBody(),
        ]);
    }

    public function end(CompletedCarrierApiRequest $event): void
    {
        if (!config('jaeger.enabled')) {
            return;
        }

        self::$scope?->getSpan()->log(array_filter([
            'response' => $event->getResponse(),
        ]));

        $this->closeSpan();
    }

    public function closeSpan(): void
    {
        self::$scope?->close();
        self::$scope = null;
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            MakingCarrierApiRequest::class,
            self::class . '@start',
        );

        $events->listen(
            CompletedCarrierApiRequest::class,
            self::class . '@end',
        );

        $events->listen(
            SuccessfulCarrierApiRequest::class,
            self::class . '@end',
        );

        $events->listen(
            FailedCarrierApiRequest::class,
            self::class . '@end',
        );

        $events->listen(
            ExceptionOccurred::class,
            self::class . '@closeSpan',
        );
    }

    private function getSpanOptions(): array
    {
        if (!$this->tracer->getActiveSpan()) {
            return [];
        }

        $spanContext = $this->tracer->getActiveSpan()->getContext();

        return [Reference::CHILD_OF => $spanContext];
    }
}
