<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Jaeger\Config;
use Jaeger\Jaeger;
use function config;
use const Jaeger\Constants\PROPAGATOR_ZIPKIN;

class JaegerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Config::class, static function () {
            $config = Config::getInstance()->gen128bit();
            $config::$propagator = PROPAGATOR_ZIPKIN;

            return $config;
        });

        $this->app->singleton(Jaeger::class, static function (Container $app) {
            $config = $app->get(Config::class);

            return $config->initTracer(
                config('jaeger.server_name'),
                config('jaeger.agent_host') . ':' . config('jaeger.agent_port')
            );
        });
    }
}
