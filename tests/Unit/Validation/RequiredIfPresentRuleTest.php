<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredIfPresentRule;

class RequiredIfPresentRuleTest extends TestCase
{
    /** @var RequiredIfPresentRule */
    protected $rule;

    public function setUp()
    {
        parent::setUp();

        $this->rule = new RequiredIfPresentRule(
            'data.attributes.pickup_location.address.city',
            'data.attributes.pickup_location.code'
        );
    }

    /** @test */
    public function testIsValid()
    {
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

        $this->assertTrue($this->rule->isValid($requestData));
        $this->assertEmpty($this->rule->getErrors());
    }

    /** @test */
    public function testNotValidWithoutValue()
    {
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

        $this->assertFalse($this->rule->isValid($requestData));
        $this->assertNotEmpty($this->rule->getErrors());
    }

    /** @test */
    public function testNotValidWithNullValue()
    {
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

        $this->assertFalse($this->rule->isValid($requestData));
        $this->assertNotEmpty($this->rule->getErrors());
    }

    /** @test */
    public function testIsValidBothMissing()
    {
        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'recipient_address' => (object)[
                        'street_1' => 'Hoofdweg 679',
                    ],
                ],
            ],
        ];

        $this->assertTrue($this->rule->isValid($requestData));
        $this->assertEmpty($this->rule->getErrors());
    }
}
