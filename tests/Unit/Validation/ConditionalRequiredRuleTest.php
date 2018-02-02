<?php

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\ConditionalRequiredRule;

class ConditionalRequiredRuleTest extends TestCase
{
    /** @test */
    public function testIsValid()
    {
        $rule = new ConditionalRequiredRule('data.attributes.pickup_location.address.city', 'data.attributes.pickup_location.code');

        $resource = (object)[
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

        $this->assertTrue($rule->isValid($resource));
        $this->assertEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidWithoutValue()
    {
        $rule = new ConditionalRequiredRule(
            'data.attributes.pickup_location.address.city',
            'data.attributes.pickup_location.code'
        );

        $resource = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'pickup_location' => (object)[
                        'code'    => '123456',
                        'address' => (object)[
                        ],
                    ],
                ],
            ],
        ];

        $this->assertFalse($rule->isValid($resource));
        $this->assertNotEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidWithNullValue()
    {
        $rule = new ConditionalRequiredRule(
            'data.attributes.pickup_location.address.city',
            'data.attributes.pickup_location.code'
        );

        $resource = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'pickup_location' => (object)[
                        'code'    => '123456',
                        'address' => (object)[
                            'city' => null,
                        ],
                    ],
                ],
            ],
        ];

        $this->assertFalse($rule->isValid($resource));
        $this->assertNotEmpty($rule->getErrors());
    }
}