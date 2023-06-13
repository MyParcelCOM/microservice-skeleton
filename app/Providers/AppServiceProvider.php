<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Aws\Sns\SnsClient;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use MyParcelCom\JsonApi\Http\Interfaces\RequestInterface;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Carrier\CarrierApiGateway;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Exceptions\Handler;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Rules\CombinedFieldsMaxRule;
use MyParcelCom\Microservice\Rules\RequiredIfInternationalRule;
use Psr\Log\LoggerInterface;

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
                ->setDebug((bool) config('app.debug'))
                ->setLogger($app->make(LoggerInterface::class));

            if (config('app.links.contact_page') !== null) {
                $handler->setContactLink((string) config('app.links.contact_page'));
            }

            return $handler;
        });

        $this->app->singleton(CarrierApiGatewayInterface::class, CarrierApiGateway::class);

        $this->app->singleton(HttpClient::class);

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

        $this->app->bind(TransformerService::class, function (Container $app) {
            $request = $app->make(Request::class);
            $service = new TransformerService($app->make(TransformerFactory::class));

            if ($request instanceof RequestInterface) {
                $service->setPaginator($request->getPaginator());
                $service->setIncludes($request->getIncludes());
            }

            return $service;
        });

        // Todo: Uncomment this when implementing SNS.
//        $this->app->bind(StatusPublisher::class, function (Container $app) {
//            $snsClient = new SnsClient(config('sns'));
//
//            return new StatusPublisher(
//                $snsClient,
//                $app->make(TransformerService::class)
//            );
//        });
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
