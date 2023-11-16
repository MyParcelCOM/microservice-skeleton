<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Illuminate\Support\ServiceProvider;
use Jaeger\Config;
use OpenTracing\GlobalTracer;
use OpenTracing\Tracer;

use const Jaeger\SAMPLER_TYPE_CONST;

class JaegerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Tracer::class, static function () {
            if (config('jaeger.enabled')) {
                $config = new Config(
                    [
                        'sampler'     => [
                            'type'  => SAMPLER_TYPE_CONST,
                            'param' => true,
                        ],
                        'logging'     => false,
                        'local_agent' => [
                            'reporting_host' => config('jaeger.agent_host'),
                            'reporting_port' => config('jaeger.agent_port'),
                        ],
                    ],
                    config('jaeger.server_name'),
                );
                $config->initializeTracer();
            }

            return GlobalTracer::get();
        });
    }
}
