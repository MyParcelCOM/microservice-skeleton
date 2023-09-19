<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Rules;

use MyParcelCom\Microservice\Rules\Sanitization\BetweenCharsSanitization;
use MyParcelCom\Microservice\Rules\Sanitization\FallbackValueSanitization;
use MyParcelCom\Microservice\Rules\Sanitization\MaxCharsCombinedSanitization;
use MyParcelCom\Microservice\Rules\Sanitization\MaxCharsSanitization;
use MyParcelCom\Microservice\Rules\Sanitization\MaxMultipliedSanitization;
use MyParcelCom\Microservice\Rules\Sanitization\MaxSumSanitization;
use MyParcelCom\Microservice\Rules\Sanitization\WithinRangeSanitization;
use MyParcelCom\Microservice\Tests\TestCase;

class SanitizationTest extends TestCase
{
    /** @test */
    public function testMaxCharsSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new MaxCharsSanitization(10);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'ć234567890']]);
        $this->assertEquals('ć234567890', data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'ć23456789']]);
        $this->assertEquals('ć23456789', data_get($sanitized, 'test.input'));

        // Test that long input gets cut off correctly
        $sanitization = new MaxCharsSanitization(10);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'ć234567890a']]);
        $this->assertEquals('ć234567890', data_get($sanitized, 'test.input'));

        // Test that long input gets cut off correctly for arrays
        $sanitization = new MaxCharsSanitization(10);
        $sanitized = $sanitization->sanitize('test.*.input', [
            'test' => [
                [
                    'input' => 'ć234567890a',
                ],
                [
                    'input' => 'ć234567890b',
                ],
            ],
        ]);
        $this->assertEquals('ć234567890', data_get($sanitized, 'test.0.input'));
        $this->assertEquals('ć234567890', data_get($sanitized, 'test.1.input'));
    }

    /** @test */
    public function testWithinRangeSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new WithinRangeSanitization(10, 100);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 10]]);
        $this->assertEquals(10, data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 100]]);
        $this->assertEquals(100, data_get($sanitized, 'test.input'));

        // Test that input outside the valid range gets corrected
        $sanitization = new WithinRangeSanitization(10, 100);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 9]]);
        $this->assertEquals(10, data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 101]]);
        $this->assertEquals(100, data_get($sanitized, 'test.input'));

        // Test that input outside the valid range gets corrected for arrays
        $sanitization = new WithinRangeSanitization(10, 100);
        $sanitized = $sanitization->sanitize('test.*.input', [
            'test' => [
                [
                    'input' => 9,
                ],
                [
                    'input' => 101,
                ],
            ],
        ]);
        $this->assertEquals(10, data_get($sanitized, 'test.0.input'));
        $this->assertEquals(100, data_get($sanitized, 'test.1.input'));
    }

    /** @test */
    public function testMaxCharsCombinedSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new MaxCharsCombinedSanitization(10, [
            'test.input1',
            'test.input2',
        ], '::');
        $sanitized = $sanitization->sanitize('0', [
            'test' => [
                'input1' => 'abćd',
                'input2' => 'fghi',
            ],
        ]);
        $this->assertEquals('abćd', data_get($sanitized, 'test.input1'));
        $this->assertEquals('fghi', data_get($sanitized, 'test.input2'));
        $sanitized = $sanitization->sanitize('0', [
            'test' => [
                'input1' => 'abć',
                'input2' => 'fgh',
            ],
        ]);
        $this->assertEquals('abć', data_get($sanitized, 'test.input1'));
        $this->assertEquals('fgh', data_get($sanitized, 'test.input2'));

        // Test that long input gets cut off correctly
        $sanitization = new MaxCharsCombinedSanitization(10, [
            'test.input1',
            'test.input2',
        ], '::');
        $sanitized = $sanitization->sanitize('0', [
            'test' => [
                'input1' => 'abćdef',
                'input2' => 'ghijkl',
            ],
        ]);
        $this->assertEquals('abćdef', data_get($sanitized, 'test.input1'));
        // Only 2 chars because 6 (input1) + spacer (2) + 2 = 10 (max)
        $this->assertEquals('gh', data_get($sanitized, 'test.input2'));

        // Test that long input gets cut off correctly for arrays
        $sanitization = new MaxCharsCombinedSanitization(10, [
            'test.*.input1',
            'test.*.input2',
        ], '::');
        $sanitized = $sanitization->sanitize('0', [
            'test' => [
                [
                    'input1' => 'abćdef',
                    'input2' => 'ghijkl',
                ],
                [
                    'input1' => 'mnopqr',
                    'input2' => 'stovuw',
                ],
            ],
        ]);
        $this->assertEquals('abćdef', data_get($sanitized, 'test.0.input1'));
        $this->assertEquals('gh', data_get($sanitized, 'test.0.input2'));
        $this->assertEquals('mnopqr', data_get($sanitized, 'test.1.input1'));
        $this->assertEquals('st', data_get($sanitized, 'test.1.input2'));
    }

    /** @test */
    public function testMaxSumSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new MaxSumSanitization(10, [
            'test.input1',
            'test.input2',
            'test.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', [
            'test' => [
                'input1' => 2,
                'input2' => 5,
                'input3' => 3,
            ],
        ]);
        $this->assertEquals(2, data_get($sanitized, 'test.input1'));
        $this->assertEquals(5, data_get($sanitized, 'test.input2'));
        $this->assertEquals(3, data_get($sanitized, 'test.input3'));

        // Test that wrong input gets corrected
        $sanitization = new MaxSumSanitization(10, [
            'test.input1',
            'test.input2',
            'test.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', [
            'test' => [
                'input1' => 3,
                'input2' => 5,
                'input3' => 3,
            ],
        ]);
        $this->assertEquals(3, data_get($sanitized, 'test.input1'));
        $this->assertEquals(3, data_get($sanitized, 'test.input2'));
        $this->assertEquals(3, data_get($sanitized, 'test.input3'));

        // Test that wrong input gets corrected for arrays
        $sanitization = new MaxSumSanitization(10, [
            'test.*.input1',
            'test.*.input2',
            'test.*.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', [
            'test' => [
                [
                    'input1' => 3,
                    'input2' => 5,
                    'input3' => 3,
                ],
                [
                    'input1' => 9,
                    'input2' => 9,
                    'input3' => 9,
                ],
            ],
        ]);
        $this->assertEquals(3, data_get($sanitized, 'test.0.input1'));
        $this->assertEquals(3, data_get($sanitized, 'test.0.input2'));
        $this->assertEquals(3, data_get($sanitized, 'test.0.input3'));
        $this->assertEquals(3, data_get($sanitized, 'test.1.input1'));
        $this->assertEquals(3, data_get($sanitized, 'test.1.input2'));
        $this->assertEquals(3, data_get($sanitized, 'test.1.input3'));
    }

    /** @test */
    public function testMaxMultipliedSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new MaxMultipliedSanitization(24, [
            'test.input1',
            'test.input2',
            'test.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', [
            'test' => [
                'input1' => 2,
                'input2' => 3,
                'input3' => 4,
            ],
        ]);
        $this->assertEquals(2, data_get($sanitized, 'test.input1'));
        $this->assertEquals(3, data_get($sanitized, 'test.input2'));
        $this->assertEquals(4, data_get($sanitized, 'test.input3'));

        // Test that wrong input gets corrected
        $sanitization = new MaxMultipliedSanitization(23, [
            'test.input1',
            'test.input2',
            'test.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', [
            'test' => [
                'input1' => 2,
                'input2' => 3,
                'input3' => 4,
            ],
        ]);
        $this->assertEquals(2, data_get($sanitized, 'test.input1'));
        $this->assertEquals(2, data_get($sanitized, 'test.input2'));
        $this->assertEquals(2, data_get($sanitized, 'test.input3'));

        // Test that wrong input gets corrected for arrays
        $sanitization = new MaxMultipliedSanitization(23, [
            'test.*.input1',
            'test.*.input2',
            'test.*.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', [
            'test' => [
                [
                    'input1' => 2,
                    'input2' => 3,
                    'input3' => 4,
                ],
                [
                    'input1' => 9,
                    'input2' => 9,
                    'input3' => 9,
                ],
            ],
        ]);
        $this->assertEquals(2, data_get($sanitized, 'test.0.input1'));
        $this->assertEquals(2, data_get($sanitized, 'test.0.input2'));
        $this->assertEquals(2, data_get($sanitized, 'test.0.input3'));
        $this->assertEquals(2, data_get($sanitized, 'test.1.input1'));
        $this->assertEquals(2, data_get($sanitized, 'test.1.input2'));
        $this->assertEquals(2, data_get($sanitized, 'test.1.input3'));
    }

    /** @test */
    public function testBetweenCharsSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new BetweenCharsSanitization(4, 5);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'abćd']]);
        $this->assertEquals('abćd', data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'abćde']]);
        $this->assertEquals('abćde', data_get($sanitized, 'test.input'));

        // If there are not enough chars, some should be added
        $sanitization = new BetweenCharsSanitization(4, 6);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'ab']]);
        $this->assertEquals('abXX', data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'abć']]);
        $this->assertEquals('abćX', data_get($sanitized, 'test.input'));

        // If there are too much chars, some should be removed
        $sanitization = new BetweenCharsSanitization(4, 6);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'abćdefgh']]);
        $this->assertEquals('abćdef', data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => 'abćdefg']]);
        $this->assertEquals('abćdef', data_get($sanitized, 'test.input'));

        // Test that wrong input gets corrected for arrays
        $sanitization = new BetweenCharsSanitization(4, 6);
        $sanitized = $sanitization->sanitize('test.*.input', [
            'test' => [
                [
                    'input' => 'abć',
                ],
                [
                    'input' => 'abćdefg',
                ],
            ],
        ]);
        $this->assertEquals('abćX', data_get($sanitized, 'test.0.input'));
        $this->assertEquals('abćdef', data_get($sanitized, 'test.1.input'));
    }

    /** @test */
    public function testFallbackValueSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new FallbackValueSanitization(null);
        $sanitized = $sanitization->sanitize(
            'test.input',
            ['test' => ['input' => '+39312345678']],
            [
                'test.input' => ['regex:/\+3\d+/'],
                'test.other' => 'required|string|min:5',
            ]
        );
        $this->assertEquals('+39312345678', data_get($sanitized, 'test.input'));

        // If input is not valid, the value should get set to the fallback
        $sanitization = new FallbackValueSanitization('fallback');
        $sanitized = $sanitization->sanitize(
            'test.input',
            ['test' => ['input' => '+49312345678']],
            [
                'test.input' => ['regex:/\+3\d+/'],
                'test.other' => 'required|string|min:5',
            ]
        );
        $this->assertEquals('fallback', data_get($sanitized, 'test.input'));

        // Same as above, but with null as the fallback
        $sanitization = new FallbackValueSanitization(null);
        $sanitized = $sanitization->sanitize(
            'test.input',
            ['test' => ['input' => '+49312345678']],
            [
                'test.input' => ['regex:/\+3\d+/'],
                'test.other' => 'required|string|min:5',
            ]
        );
        $this->assertNull(data_get($sanitized, 'test.input'));

        // Test that wrong input gets corrected for arrays
        $sanitization = new FallbackValueSanitization(123);
        $sanitized = $sanitization->sanitize('test.*.input', [
            'test' => [
                [
                    'input' => '+39312345678',
                ],
                [
                    'input' => '+49312345678',
                ],
            ],
        ], [
            'test.*.input' => ['regex:/\+3\d+/'],
            'test.other' => 'required|string|min:5',
        ]);
        $this->assertEquals('+39312345678', data_get($sanitized, 'test.0.input'));
        $this->assertEquals(123, data_get($sanitized, 'test.1.input'));
    }
}
