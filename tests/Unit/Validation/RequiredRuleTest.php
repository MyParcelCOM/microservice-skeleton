<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredRule;

class RequiredRuleTest extends TestCase
{
    /** @test */
    public function testIsValid()
    {
        $rule = new RequiredRule('recipient_address.postal_code');

        $resource = (object)[
            'recipient_address' => (object)[
                'postal_code' => '1234AB',
            ],
        ];

        $this->assertTrue(
            $rule->isValid($resource)
        );
    }

    /** @test */
    public function testNotValidWithoutValue()
    {
        $rule = new RequiredRule('recipient_address.postal_code');

        $resource = (object)[
            'recipient_address' => (object)[
            ],
        ];

        $this->assertFalse(
            $rule->isValid($resource)
        );
    }

    /** @test */
    public function testNotValidNullValue()
    {
        $rule = new RequiredRule('recipient_address.postal_code');

        $resource = (object)[
            'recipient_address' => (object)[
                'postal_code' => null,
            ],
        ];

        $this->assertFalse(
            $rule->isValid($resource)
        );
    }
}
