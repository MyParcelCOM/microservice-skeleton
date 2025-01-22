<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;

class JsonSchemaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('schema', function () {
            return json_decode(
                file_get_contents(
                    base_path(config('transformer.schemas.carrier')),
                ),
            );
        });

        $this->app->singleton(Validator::class, function (Container $app) {
            $schemaStorage = new SchemaStorage();
            $schemaStorage->addSchema('file://' . config('transformer.schemas.carrier'), $app->make('schema'));

            return new Validator(new Factory($schemaStorage));
        });

        $this->app->bind(JsonRequestValidator::class, function (Container $app) {
            return new JsonRequestValidator(
                $app->make(Request::class),
                $app->make('schema'),
                $app->make(Validator::class),
            );
        });
    }
}
