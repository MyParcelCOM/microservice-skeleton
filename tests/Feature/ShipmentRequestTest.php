<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use Illuminate\Support\Arr;
use Mockery;
use MyParcelCom\Microservice\Http\ShipmentRequest;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use function GuzzleHttp\json_decode;

class ShipmentRequestTest extends TestCase
{
    use CommunicatesWithCarrier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bindCarrierApiGatewayMock();
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
                    'detail' => 'The shipment\'s description is required.',
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
                    'detail' => 'The recipient\'s phone number must be between 8 and 15 characters.',
                    'source' => [
                        'pointer' => 'data/attributes/recipient_address/phone_number',
                    ],
                ],
                [
                    'title'  => 'Invalid input',
                    'detail' => 'The recipient\'s phone number is incorrectly formatted.',
                    'source' => [
                        'pointer' => 'data/attributes/recipient_address/phone_number',
                    ],
                ],
                [
                    'title'  => 'Invalid input',
                    'detail' => 'The used service code must be one of the following: service-a, service-b, service-c.',
                    'source' => [
                        'pointer' => 'data/attributes/service/code',
                    ],
                ],
                [
                    'title'  => 'Missing input',
                    'detail' => 'The sender\'s email address is required when sender\'s phone number is not present.',
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

    /**
     * @return array
     */
    protected function getShipmentRequestBody(): array
    {
        $requestStub = file_get_contents(base_path('tests/Stubs/shipment-request.stub'));

        return json_decode($requestStub, true);
    }

    /**
     * @param array $rules
     */
    protected function registerShipmentRequestWithRules(array $rules = []): void
    {
        $this->app->singleton(ShipmentRequest::class, function () use ($rules) {
            return Mockery::mock(ShipmentRequest::class, [
                'rules' => $rules,
            ])->makePartial();
        });
    }
}
