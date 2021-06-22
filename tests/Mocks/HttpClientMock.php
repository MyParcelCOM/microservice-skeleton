<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Mocks;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\Create as CreatePromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Exception;

class HttpClientMock extends HttpClient
{
    /** @var MockedClientResponse[][] $mockedClientResponses */
    private $mockedClientResponses = [];

    /**
     * @inheritDoc
     */
    public function requestAsync($method, $uri = '', array $options = []): PromiseInterface
    {
        $method = strtolower($method);
        $key = strtolower("$method $uri");

        // Mock a custom response or exception if we wanted too
        $mockedResponses = $this->mockedClientResponses[$key] ?? null;
        if (is_array($mockedResponses)) {
            $mockedResponse = count($mockedResponses) > 1
                ? array_shift($this->mockedClientResponses[$key])
                : reset($mockedResponses);

            if ($mockedResponse instanceof MockedClientException) {
                return CreatePromise::rejectionFor($mockedResponse->toClientException());
            } elseif ($mockedResponse instanceof MockedClientResponse) {
                return CreatePromise::promiseFor($mockedResponse->toClientResponse());
            }
        }

        // Try to mock a default '200 OK' response from a stub
        $body = $this->getResponseStub($method, $uri);
        $response = new Response(200, [], $body);
        return CreatePromise::promiseFor($response);
    }

    /**
     * @param MockedClientResponse $response
     */
    public function mockClientResponse(MockedClientResponse $response)
    {
        $method = $response->getMethod();
        $uri = $response->getUri();
        $key = strtolower("$method $uri");
        if (!array_key_exists($key, $this->mockedClientResponses)) {
            $this->mockedClientResponses[$key] = [];
        }
        $this->mockedClientResponses[$key][] = $response;
    }

    /**
     * @param string $method
     * @param string $url
     * @return string
     */
    private function getResponseStub(string $method, string $url): string
    {
        $stubPath = base_path(
            'tests/Stubs/'
            . $method . '-' . str_replace('/', '-', $url)
            . '.stub'
        );

        if (!file_exists($stubPath)) {
            throw new Exception(
                sprintf(
                    'The stub response file `%s` does not exist. Please create it and add a response to it for a `%s` request to `%s`',
                    $stubPath,
                    $method,
                    $url
                )
            );
        }

        return file_get_contents($stubPath);
    }
}
