<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Com\Tecnick\Barcode\Barcode;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Illuminate\Support\ServiceProvider;
use MyParcelCom\Common\Contracts\JsonApiRequestInterface;
use MyParcelCom\Common\Contracts\UrlGeneratorInterface;
use MyParcelCom\Microservice\Exceptions\Handler;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Routing\UrlGenerator;
use MyParcelCom\Microservice\Shipments\ShipmentMapper;
use MyParcelCom\Transformers\TransformerFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('request', Request::class);
        $this->app->alias('request', JsonApiRequestInterface::class);

        $this->app->singleton(ExceptionHandler::class, function (Container $app) {
            $handler = (new Handler($app))
                ->setResponseFactory($app->make(ResponseFactory::class))
                ->setDebug(config('app.debug'));

            if (config('app.links.contact_page') !== null) {
                $handler->setContactLink(config('app.links.contact_page'));
            }

            if (extension_loaded('newrelic')) {
                newrelic_set_appname(config('app.name'));
                $handler->setNewrelic($app->make('newrelic'));
            }

            return $handler;
        });

        $this->app->singleton(LaravelUrlGenerator::class, UrlGenerator::class);

        $this->app->singleton(UrlGeneratorInterface::class, UrlGenerator::class);

        $this->app->singleton(TransformerFactory::class, function (Container $app) {
            return (new TransformerFactory($app->make(UrlGeneratorInterface::class)))
                ->setMapping(config('transformer.mapping'));
        });

        $this->app->singleton(ShipmentMapper::class, function (Container $app) {
            return (new ShipmentMapper())
                ->setBarcodeGenerator($app->make(Barcode::class));
        });
    }
}
