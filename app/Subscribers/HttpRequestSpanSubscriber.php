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
use Jaeger\Config;
use Jaeger\Jaeger;
use Jaeger\Scope;
use OpenTracing\Reference;
use OpenTracing\SpanContext;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use function in_array;

use const OpenTracing\Formats\TEXT_MAP;

class HttpRequestSpanSubscriber
{
    /**
     * @var Jaeger
     */
    protected $tracer;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Scope
     */
    private static $scope;

    /**
     * Create the event listener.
     *
     * @param Jaeger  $tracer
     * @param Config  $config
     * @param Request $request
     */
    public function __construct(Jaeger $tracer, Config $config, Request $request)
    {
        $this->tracer = $tracer;
        $this->config = $config;
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

        $scope = $this->tracer->startActiveSpan($this->getOperationName($event), $this->getSpanOptions());
        $scope->getSpan()->startTime = (int) (LARAVEL_START * 1000000);
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
            $this->config->flush();
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
        $spanContext = $this->extractJaegerSpanContext($this->tracer);

        return ($spanContext) ? [Reference::CHILD_OF => $spanContext] : [];
    }

    private function getOperationName(RouteMatched $event): string
    {
        return $event->request->getMethod() . ' ' . Route::currentRouteAction();
    }

    private function extractJaegerSpanContext(Jaeger $tracer): ?SpanContext
    {
        $contextCarrier = (new Collection($this->request->headers))->map(static function ($value) {
            return Arr::first($value);
        })->all();

        return $tracer->extract(TEXT_MAP, $contextCarrier);
    }
}
