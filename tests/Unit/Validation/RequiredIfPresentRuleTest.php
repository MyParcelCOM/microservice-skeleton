<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredIfPresentRule;

class RequiredIfPresentRuleTest extends TestCase
{
    /** @test */
    public function testIsValid()
    {
        $rule = new RequiredIfPresentRule('data.attributes.pickup_location.address.city', 'data.attributes.pickup_location.code');

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
        $rule = new RequiredIfPresentRule(
            'data.attributes.pickup_location.address.city',
            'data.attributes.pickup_location.code'
        );

        $requestData = (object)[
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

        $this->assertFalse($rule->isValid($requestData));
        $this->assertNotEmpty($rule->getErrors());
    }

    /** @test */
    public function testNotValidWithNullValue()
    {
        $rule = new RequiredIfPresentRule(
            'data.attributes.pickup_location.address.city',
            'data.attributes.pickup_location.code'
        );

        $requestData = (object)[
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

        $this->assertFalse($rule->isValid($requestData));
        $this->assertNotEmpty($rule->getErrors());
    }
}