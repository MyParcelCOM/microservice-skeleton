<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Rules;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use MyParcelCom\Microservice\Tests\TestCase;
use function GuzzleHttp\json_decode;

class RequiredIfInternationalRuleTest extends TestCase
{
    /** @test */
    public function testValidationDoesNotFailForDomesticShipmentsWithInternationalRules()
    {
        $requestStub = file_get_contents(base_path('tests/Stubs/shipment-request.json'));
        $requestData = json_decode($requestStub, true);

        // The sender address country code in shipment-request is GB.
        // By making the recipient address country code also GB, it becomes domestic.
        Arr::set($requestData, 'data.attributes.recipient_address.country_code', 'GB');

        Arr::forget($requestData, 'data.attributes.description');

        /** @var Validator $validator */
        $validator = app('validator')->make($requestData, [
            'data.attributes.description' => 'required_if_international',
        ]);

        // For a domestic shipment, description is not required, and so the validator passes.
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function testItValidatesInternationalShipmentsBasedOnInternationalRequirements()
    {
        $requestStub = file_get_contents(base_path('tests/Stubs/shipment-request.json'));
        $requestData = json_decode($requestStub, true);

        Arr::forget($requestData, 'data.attributes.description');

        /** @var Validator $validator */
        $validator = app('validator')->make($requestData, [
            'data.attributes.description' => 'required_if_international',
        ]);

        // For a international shipment, description is required, and so the validator fails.
        $this->assertFalse($validator->passes());
        $errors = $validator->errors()->get('data.attributes.description');
        $this->assertEquals('The shipment\'s description is required for international shipments.', reset($errors));
    }
}
