<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredRule;

class RequiredRuleTest extends TestCase
{
    /** @test */
    public function testIsValid()
    {
        $rule = new RequiredRule('data.attributes.recipient_address.postal_code');

        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'recipient_address' => (object)[
                        'postal_code' => '1234AB',
                    ],
                ],
            ],
        ];

        $this->assertTrue(
            $rule->isValid($requestData)
        );
        $this->assertEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidWithoutValue()
    {
        $rule = new RequiredRule('data.attributes.recipient_address.postal_code');

        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'recipient_address' => (object)[
                    ],
                ],
            ],
        ];

        $this->assertFalse(
            $rule->isValid($requestData)
        );
        $this->assertNotEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidNullValue()
    {
        $rule = new RequiredRule('data.attributes.recipient_address.postal_code');

        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'recipient_address' => (object)[
                        'postal_code' => null,
                    ],
                ],
            ],
        ];

        $this->assertFalse(
            $rule->isValid($requestData)
        );
        $this->assertNotEmpty($rule->getErrors());
    }
}
