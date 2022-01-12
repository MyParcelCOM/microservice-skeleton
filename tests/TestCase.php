<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use MyParcelCom\Microservice\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Call the given URI and return the Response. Replaced parent function to use a different Request class.
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null): TestResponse
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->app->make(HttpKernel::class);

        $files = array_merge($files, $this->extractFilesFromDataArray($parameters));

        $symfonyRequest = SymfonyRequest::create(
            $this->prepareUrlForRequest($uri),
            $method,
            $parameters,
            $cookies,
            $files,
            array_replace($this->serverVariables, $server),
            $content
        );

        $response = $kernel->handle(
            $request = Request::createFromBase($symfonyRequest)
        );

        $kernel->terminate($request, $response);

        if ($this->followRedirects) {
            $response = $this->followRedirects($response);
        }

        return $this->createTestResponse($response);
    }

    /**
     * Call the given URI with a JSON request. Make parent function use json-api headers (or other defined $headers).
     */
    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge([
            'Accept'       => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ], $headers);

        return parent::json($method, $uri, $data, $headers);
    }
}
