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
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $cookies
     * @param array  $files
     * @param array  $server
     * @param null   $content
     * @return TestResponse
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null): TestResponse
    {
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

        return $this->createTestResponse($response);
    }
}
