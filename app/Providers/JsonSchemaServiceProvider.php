<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use MyParcelCom\Microservice\Http\JsonRequestValidator;

class JsonSchemaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('schema', function () {
            return json_decode(file_get_contents(
                config('transformer.schemas.carrier')
            ));
        });

        $this->app->singleton(Validator::class, function (Container $app) {
            $schemaStorage = new SchemaStorage();
            $schemaStorage->addSchema(config('transformer.schemas.carrier'), $app->make('schema'));

            return new Validator(new Factory($schemaStorage));
        });

        $this->app->singleton(JsonRequestValidator::class, function (Container $app) {
            return (new JsonRequestValidator())
                ->setRequest($app->make('request'))
                ->setSchema($app->make('schema'))
                ->setValidator($app->make(Validator::class));
        });
    }
}
