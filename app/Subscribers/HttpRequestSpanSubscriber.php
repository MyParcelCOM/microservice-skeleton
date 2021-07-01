<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Subscribers;

use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use OpenTracing\Reference;
use OpenTracing\Scope;
use OpenTracing\SpanContext;
use OpenTracing\Tracer;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use function in_array;

use const OpenTracing\Formats\HTTP_HEADERS;

class HttpRequestSpanSubscriber
{
    private Tracer $tracer;
    private Request $request;

    private static ?Scope $scope = null;

    /**
     * Create the event listener.
     *
     * @param Tracer  $tracer
     * @param Request $request
     */
    public function __construct(Tracer $tracer, Request $request)
    {
        $this->tracer = $tracer;
        $this->request = $request;
    }

    public function start(RouteMatched $event): void
    {
        if (!config('jaeger.enabled')) {
            return;
        }

        if (!$this->isRouteTraceable($event->route->getName())) {
            return;
        }

        $scope = $this->tracer->startActiveSpan(
            $this->getOperationName($event),
            $this->getSpanOptions()
        );
        $scope->getSpan()->setTag('type', 'http');

        self::$scope = $scope;
    }

    public function end(RequestHandled $event): void
    {
        if (self::$scope) {
            $this->injectSpanMeta($event->request, $event->response);
            self::$scope->close();
        }

        try {
            $this->tracer->flush();
        } catch (Throwable $e) {
            Log::critical($e->getMessage());
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
            RouteMatched::class,
            self::class . '@start'
        );
        $events->listen(
            RequestHandled::class,
            self::class . '@end'
        );
    }

    private function injectSpanMeta(Request $request, Response $response): void
    {
        $span = self::$scope->getSpan();
        $span->log([
            'successful'      => $response->isSuccessful(),
            'request.host'    => $request->getHost(),
            'request.method'  => $request->method(),
            'request.path'    => $request->path(),
            'input'           => $request->input(),
            'response.status' => $response->getStatusCode(),
        ]);

        if (!$request->route()) {
            return;
        }

        foreach ($request->route()->parameters() as $parameter => $value) {
            $span->setTag($parameter, $value);
        }
    }

    private function isRouteTraceable(?string $routeName): bool
    {
        return in_array($routeName, config('jaeger.trace_routes', []), true);
    }

    private function getSpanOptions(): array
    {
        $spanOptions = [
            'start_time' => (int) (LARAVEL_START * 1000000),
        ];

        $spanContext = $this->extractJaegerSpanContext($this->tracer);
        if ($spanContext) {
            $spanOptions[Reference::CHILD_OF] = $spanContext;
        }

        return $spanOptions;
    }

    private function getOperationName(RouteMatched $event): string
    {
        return $event->request->getMethod() . ' ' . Route::currentRouteAction();
    }

    private function extractJaegerSpanContext(Tracer $tracer): ?SpanContext
    {
        $contextCarrier = (new Collection($this->request->headers))->map(static function ($value) {
            return Arr::first($value);
        })->all();

        return $tracer->extract(HTTP_HEADERS, $contextCarrier);
    }
}
