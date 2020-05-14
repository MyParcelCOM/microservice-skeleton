<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Exceptions;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use MyParcelCom\Microservice\Exceptions\Handler;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function testItTransformsAValidationExceptionIntoAMultiErrorException()
    {
        $containerMock = Mockery::mock(Container::class);
        $handler = new Handler($containerMock);

        $responseFactoryMock = Mockery::mock(ResponseFactory::class);
        $responseFactoryMock->shouldReceive('json')->andReturnUsing(function ($response) {
            $this->assertEquals([
                'errors' => [
                    [
                        'title'  => 'Missing input',
                        'detail' => 'You are missing required input bro!',
                        'source' => [
                            'pointer' => 'some/missing/pointer',
                        ],
                    ],
                    [
                        'title'  => 'Invalid input',
                        'detail' => 'Your input is invalid yo!',
                        'source' => [
                            'pointer' => 'some/invalid/pointer',
                        ],
                    ],
                ],
            ], $response);
        });
        $handler->setResponseFactory($responseFactoryMock);

        $requestMock = Mockery::mock(Request::class);

        $messageBag = Mockery::mock(MessageBag::class);
        $messageBag->shouldReceive('get')->once()->with('some.missing.pointer')->andReturn(['You are missing required input bro!']);
        $messageBag->shouldReceive('get')->once()->with('some.invalid.pointer')->andReturn(['Your input is invalid yo!']);

        $validator = Mockery::mock(Validator::class, [
            'failed' => [
                'some.missing.pointer' => [],
                'some.invalid.pointer' => [],
            ],
            'errors' => $messageBag,
        ]);

        $validationException = Mockery::mock(ValidationException::class);
        $validationException->validator = $validator;

        $handler->render($requestMock, $validationException);
    }
}
