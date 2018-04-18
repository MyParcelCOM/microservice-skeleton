<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Com\Tecnick\Barcode\Barcode;
use GuzzleHttp\Client;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Illuminate\Support\ServiceProvider;
use MyParcelCom\JsonApi\Http\Interfaces\RequestInterface;
use MyParcelCom\JsonApi\Interfaces\UrlGeneratorInterface;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Exceptions\Handler;
use MyParcelCom\Microservice\Geo\GeoService;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Routing\UrlGenerator;
use MyParcelCom\Microservice\Shipments\ShipmentMapper;

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
        $this->app->alias('request', RequestInterface::class);

        $this->app->singleton(ExceptionHandler::class, function (Container $app) {
            $handler = (new Handler($app))
                ->setResponseFactory($app->make(ResponseFactory::class))
                ->setDebug((bool)config('app.debug'));

            if (config('app.links.contact_page') !== null) {
                $handler->setContactLink((string)config('app.links.contact_page'));
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
            return (new TransformerFactory())
                ->setDependencies([
                    AbstractTransformer::class => [
                        'setUrlGenerator' => function () use ($app) {
                            return $app->make(UrlGeneratorInterface::class);
                        },
                    ],
                ])
                ->setMapping(config('transformer.mapping'));
        });

        $this->app->singleton(ShipmentMapper::class, function (Container $app) {
            return (new ShipmentMapper())
                ->setBarcodeGenerator($app->make(Barcode::class));
        });

        $this->app->singleton(GeoService::class, function (Container $app) {
            return (new GeoService())
                ->setCache($app->make('cache.store'))
                ->setBaseUrl((string)config('address.service.url'))
                ->setSecret((string)config('address.service.secret'))
                ->setClient(new Client());
        });
    }
}
