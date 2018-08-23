<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredIfMissingRule;

class RequiredIfMissingRuleTest extends TestCase
{
    /** @var RequiredIfMissingRule */
    private $rule;

    public function setUp()
    {
        parent::setUp();

        $this->rule = new RequiredIfMissingRule(
            'data.attributes.pickup_location',
            'data.attributes.recipient_address'
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
    public function testNotValidBothMissing()
    {
        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'sender_address' => (object)[
                        'street_1' => 'Hoofdweg 679',
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
                    'pickup_location' => null,
                ],
            ],
        ];

        $this->assertFalse($this->rule->isValid($requestData));
        $this->assertNotEmpty($this->rule->getErrors());
    }

    /** @test */
    public function testIsValidBothPresent()
    {
        $requestData = (object)[
            'data' => (object)[
                'attributes' => (object)[
                    'recipient_address' => (object)[
                        'street_1' => 'Hoofdweg 679',
                    ],
                    'pickup_location'   => (object)[
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
}
