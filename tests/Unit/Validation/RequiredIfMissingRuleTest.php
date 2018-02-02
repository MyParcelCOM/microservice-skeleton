<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredIfMissingRule;

class RequiredIfMissingRuleTest extends TestCase
{
    /** @test */
    public function testIsValid()
    {
        $rule = new RequiredIfMissingRule(
            'data.attributes.pickup_location',
            'data.attributes.recipient_address'
        );

        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'pickup_location' => (object)[
                        'code'    => '123456',
                        'address' => (object)[
                            'city' => 'Amsterdam',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertTrue($rule->isValid($requestData));
        $this->assertEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidWithoutValue()
    {
        $rule = new RequiredIfMissingRule(
            'data.attributes.pickup_location',
            'data.attributes.recipient_address'
        );

        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'sender_address' => (object)[
                        'street_1' => 'Hoofdweg 679',
                    ],
                ],
            ],
        ];

        $this->assertFalse($rule->isValid($requestData));
        $this->assertNotEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidWithNullValue()
    {
        $rule = new RequiredIfMissingRule(
            'data.attributes.pickup_location',
            'data.attributes.recipient_address'
        );

        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'pickup_location' => null,
                ],
            ],
        ];

        $this->assertFalse($rule->isValid($requestData));
        $this->assertNotEmpty($rule->getErrors());
    }
}
