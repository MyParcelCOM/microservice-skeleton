<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use GuzzleHttp\Utils;
use Illuminate\Support\Arr;
use Mockery;
use Mockery\MockInterface;
use MyParcelCom\Microservice\Http\ShipmentRequest;
use MyParcelCom\Microservice\Rules\Sanitization\MaxCharsSanitization;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use Symfony\Component\HttpFoundation\ParameterBag;

class ShipmentRequestTest extends TestCase
{
    use CommunicatesWithCarrier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bindHttpClientMock();
    }

    /** @test */
    public function testItReturnsAnErrorIfValidationFails()
    {
        $this->registerShipmentRequestWithRules([
            'data.attributes.description' => 'required',
        ]);

        $requestBody = $this->getShipmentRequestBody();
        Arr::forget($requestBody, 'data.attributes.description');

        $response = $this->json('post', '/shipments', $requestBody, $this->getRequestHeaders());

        $response->assertJson([
            'errors' => [
                [
                    'title'  => 'Missing input',
                    'detail' => 'The shipment description is required.',
                    'source' => [
                        'pointer' => 'data/attributes/description',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function testItReturnsMultipleErrorsIfMultipleRulesFail()
    {
        $this->registerShipmentRequestWithRules([
            'data.attributes.recipient_address.phone_number' => 'string|between:8,15|regex:/^\+?[0-9-]*$/',
            'data.attributes.service.code'                   => 'required|in:service-a,service-b,service-c',
            'data.attributes.items.*.item_weight'            => 'integer|min:1000',
            'data.attributes.sender_address.email'           => 'required_without:data.attributes.sender_address.phone_number',
        ]);

        $requestBody = $this->getShipmentRequestBody();

        Arr::set($requestBody, 'data.attributes.recipient_address.phone_number', 'nope');
        Arr::set($requestBody, 'data.attributes.service.code', 'not-one-of-the-service-codes');
        Arr::set($requestBody, 'data.attributes.items.1.item_weight', 20);
        Arr::forget($requestBody, 'data.attributes.sender_address.email');
        Arr::forget($requestBody, 'data.attributes.sender_address.phone_number');

        $response = $this->json('post', '/shipments', $requestBody, $this->getRequestHeaders());

        $response->assertJson([
            'errors' => [
                [
                    'title'  => 'Invalid input',
                    'detail' => 'The recipient phone number must be between 8 and 15 characters.',
                    'source' => [
                        'pointer' => 'data/attributes/recipient_address/phone_number',
                    ],
                ],
                [
                    'title'  => 'Invalid input',
                    'detail' => 'The recipient phone number is incorrectly formatted.',
                    'source' => [
                        'pointer' => 'data/attributes/recipient_address/phone_number',
                    ],
                ],
                [
                    'title'  => 'Invalid input',
                    'detail' => 'The service code must be one of the following: service-a, service-b, service-c.',
                    'source' => [
                        'pointer' => 'data/attributes/service/code',
                    ],
                ],
                [
                    'title'  => 'Missing input',
                    'detail' => 'The sender email address is required when sender phone number is not present.',
                    'source' => [
                        'pointer' => 'data/attributes/sender_address/email',
                    ],
                ],
                [
                    'title'  => 'Invalid input',
                    'detail' => 'The shipment item weight must be at least 1000.',
                    'source' => [
                        'pointer' => 'data/attributes/items/1/item_weight',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function testItSanitizesIncomingData()
    {
        $parameters = $this->getShipmentRequestBody();
        data_set($parameters, 'data.attributes.description', '1234');

        $request = $this->getShipmentRequestMock();
        $request
            // No sanitization first time, but do apply sanitization second time
            ->shouldReceive('sanitizationAfterValidation')
            ->andReturn([], [
                'data.attributes.description' => new MaxCharsSanitization(3),
            ])
            ->shouldReceive('getInputSource')
            ->andReturn(new ParameterBag($parameters));

        $request->query = new ParameterBag([]);
        $request->files = new ParameterBag([]);

        /** @var ShipmentRequest $request */

        // First time it should not be sanitized
        $this->assertEquals('1234', data_get($request->all(), 'data.attributes.description'));

        // Second time it should be sanitized
        $this->assertEquals('123', data_get($request->all(), 'data.attributes.description'));
    }

    protected function getShipmentRequestBody(): array
    {
        $requestStub = file_get_contents(base_path('tests/Stubs/shipment-request.json'));

        return Utils::jsonDecode($requestStub, true);
    }

    protected function registerShipmentRequestWithRules(array $rules = []): void
    {
        $this->app->singleton(ShipmentRequest::class, function () use ($rules) {
            $mock = $this->getShipmentRequestMock();
            $mock->shouldReceive('rules')->andReturn($rules);

            return $mock;
        });
    }

    private function getShipmentRequestMock(): MockInterface
    {
        $mock = Mockery::mock(ShipmentRequest::class);

        // We need to mock a few protected methods
        // Because we want to simulate a few extra sanitization rules
        $mock->shouldAllowMockingProtectedMethods();

        return $mock->makePartial();
    }
}
