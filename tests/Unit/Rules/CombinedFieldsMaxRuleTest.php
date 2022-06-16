<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Rules;

use Illuminate\Validation\Validator;
use MyParcelCom\Microservice\Tests\TestCase;

class CombinedFieldsMaxRuleTest extends TestCase
{
    /** @test */
    public function testItPassesWhenTheCombinedFieldsDoNotExceedMaxCharacterLength()
    {
        $data = [
            'fields' => [
                'field-1' => 'is 21 ćharacters long',
                'field-2' => 'is 4',
                'field-3' => 'is 9 long',
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'fields.field-1' => 'combined_fields_max:36,fields.field-2,fields.field-3',
        ]);

        // Total amount of characters is 34, plus a space for each added input is 36.
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function testItFailsWhenCombinedInputsExceedMaxCharacterLength()
    {
        $data = [
            'fields' => [
                'field-1' => 'is 21 ćharacters long',
                'field-2' => 'is 4',
                'field-3' => 'is 9 long',
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'fields.field-1' => 'combined_fields_max:35,fields.field-2,fields.field-3',
        ]);

        // Total amount of characters is 34, plus a space for each added input is 36.
        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function testItAccountsForAnExtraSpaceForEachAddedInput()
    {
        $data = [
            'fields' => [
                'field-1' => '6 long',
                'field-2' => 'is 21 ćharacters long',
                'field-3' => '13 characters',
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'fields.field-1' => 'combined_fields_max:41,fields.field-2,fields.field-3',
        ]);

        // Even though the total amount of characters (40) is below the max amount (41),
        // the rule still fails because we add a space for each added input,
        // which makes the total (42) exceed the max amount (41).
        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function testItContainsTheMaxAmountOfCharactersInTheMessage()
    {
        $data = [
            'fields' => [
                'field-1' => '6 long',
                'field-2' => 'is 21 ćharacters long',
                'field-3' => '13 characters',
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'fields.field-1' => 'combined_fields_max:41,fields.field-2,fields.field-3',
        ]);

        $errorMessages = $validator->errors()->get('fields.field-1');
        $this->assertStringContainsString('41 characters', reset($errorMessages));
    }

    /** @test */
    public function testItFiltersEmptyInputsToCalculateMaxCharacterLength()
    {
        $data = [
            'fields' => [
                'field-1' => '6 long',
                'field-2' => '',
                'field-3' => '18 ćharacters long',
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'fields.field-1' => 'combined_fields_max:25,fields.field-2,fields.field-3',
        ]);

        // The total amount of characters is 24, plus a space for each added input would be 26,
        // which would exceed the max of 25. Because one of the inputs is empty however,
        // we filter it and thus do not add a space, which changes the total to 25.
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function testItMentionsTheAddedInputsInTheErrorMessage()
    {
        $data = [
            'fields' => [
                'field-1' => '6 long',
                'field-2' => 'is 9 long',
                'field-3' => '18 ćharacters long',
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'fields.field-1' => 'combined_fields_max:25,fields.field-2,fields.field-3',
        ]);

        $this->assertFalse($validator->passes());

        $errors = $validator->errors()->get('fields.field-1');

        $this->assertStringContainsString('fields.field-1', reset($errors));
        $this->assertStringContainsString('fields.field-2', reset($errors));
        $this->assertStringContainsString('fields.field-3', reset($errors));
    }

    /** @test */
    public function testItReplacesKnownAttributesToNicerNames()
    {
        $data = [
            'data' => [
                'attributes' => [
                    'recipient_address' => [
                        'street_1'             => '6 long',
                        'street_number'        => 'is 9 long',
                        'street_number_suffix' => '18 ćharacters long',
                    ],
                ],
            ],
        ];

        /** @var Validator $validator */
        $validator = app('validator')->make($data, [
            'data.attributes.recipient_address.street_1' => 'combined_fields_max:25,data.attributes.recipient_address.street_number,data.attributes.recipient_address.street_number_suffix',
        ]);

        $this->assertFalse($validator->passes());

        $errors = $validator->errors()->get('data.attributes.recipient_address.street_1');

        // These custom attribute names can be found in /resources/lang/en/validation.php under attributes.
        $this->assertStringContainsString('recipient address street 1', reset($errors));
        $this->assertStringContainsString('recipient address street number', reset($errors));
        $this->assertStringContainsString('recipient address street number suffix', reset($errors));
    }
}
