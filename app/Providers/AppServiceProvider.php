<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Com\Tecnick\Barcode\Barcode;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use MyParcelCom\JsonApi\Http\Interfaces\RequestInterface;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Exceptions\Handler;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Rules\CombinedFieldsMaxRule;
use MyParcelCom\Microservice\Rules\RequiredIfInternationalRule;
use MyParcelCom\Microservice\Shipments\ShipmentMapper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->alias('request', Request::class);
        $this->app->alias('request', RequestInterface::class);

        $this->app->singleton(ExceptionHandler::class, function (Container $app) {
            $handler = (new Handler($app))
                ->setResponseFactory($app->make(ResponseFactory::class))
                ->setDebug((bool) config('app.debug'));

            if (config('app.links.contact_page') !== null) {
                $handler->setContactLink((string) config('app.links.contact_page'));
            }

            if (extension_loaded('newrelic')) {
                newrelic_set_appname(config('app.name'));
                $handler->setNewrelic($app->make('newrelic'));
            }

            return $handler;
        });

        $this->app->singleton(TransformerFactory::class, function (Container $app) {
            return (new TransformerFactory())
                ->setDependencies([
                    AbstractTransformer::class => [
                        'setUrlGenerator' => function () use ($app) {
                            return $app->make(UrlGenerator::class);
                        },
                    ],
                ])
                ->setMapping(config('transformer.mapping'));
        });

        $this->app->singleton(ShipmentMapper::class, function (Container $app) {
            return (new ShipmentMapper())
                ->setBarcodeGenerator($app->make(Barcode::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('combined_fields_max', CombinedFieldsMaxRule::class . '@validate');
        Validator::replacer('combined_fields_max', CombinedFieldsMaxRule::class . '@placeholders');
        Validator::extendImplicit('required_if_international', RequiredIfInternationalRule::class . '@validate');
    }
}
