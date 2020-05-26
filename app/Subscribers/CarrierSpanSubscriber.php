<?php
declare(strict_types=1);

namespace MyParcelCom\Microservice\Subscribers;

use Illuminate\Events\Dispatcher;
use Jaeger\Jaeger;
use Jaeger\Scope;
use MyParcelCom\Microservice\Events\FailedCarrierApiRequest;
use MyParcelCom\Microservice\Events\MakingCarrierApiRequest;
use MyParcelCom\Microservice\Events\SuccessfulCarrierApiRequest;
use OpenTracing\Reference;

class CarrierSpanSubscriber
{
    /**
     * @var Jaeger
     */
    protected $tracer;

    /**
     * @var Scope
     */
    private static $scope;

    /**
     * Create the event listener.
     *
     * @param Jaeger $tracer
     */
    public function __construct(Jaeger $tracer)
    {
        $this->tracer = $tracer;
    }

    public function start(MakingCarrierApiRequest $event): void
    {
        if (!config('jaeger.enabled')) {
            return;
        }
        self::$scope = $this->tracer->startActiveSpan(
            $event->getMethod() . ' ' . $event->getUrl(),
            $this->getSpanOptions()
        );
    }

    public function end(): void
    {
        if (self::$scope) {
            self::$scope->close();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            MakingCarrierApiRequest::class,
            self::class . '@start'
        );

        $events->listen(
            SuccessfulCarrierApiRequest::class,
            self::class . '@end'
        );

        $events->listen(
            FailedCarrierApiRequest::class,
            self::class . '@end'
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
