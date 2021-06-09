<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Rules;

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
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => '1234567890']]);
        $this->assertEquals('1234567890', data_get($sanitized, 'test.input'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => '123456789']]);
        $this->assertEquals('123456789', data_get($sanitized, 'test.input'));

        // Test that long input gets cut off correctly
        $sanitization = new MaxCharsSanitization(10);
        $sanitized = $sanitization->sanitize('test.input', ['test' => ['input' => '1234567890a']]);
        $this->assertEquals('1234567890', data_get($sanitized, 'test.input'));
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
    }

    /** @test */
    public function testMaxCharsCombinedSanitizationWorks()
    {
        // Test that valid input doesn't change
        $sanitization = new MaxCharsCombinedSanitization(10, [
            'test.input1',
            'test.input2',
        ], '::');
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 'abcd',
            'input2' => 'fghi',
        ]]);
        $this->assertEquals('abcd', data_get($sanitized, 'test.input1'));
        $this->assertEquals('fghi', data_get($sanitized, 'test.input2'));
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 'abc',
            'input2' => 'fgh',
        ]]);
        $this->assertEquals('abc', data_get($sanitized, 'test.input1'));
        $this->assertEquals('fgh', data_get($sanitized, 'test.input2'));

        // Test that long input gets cut off correctly
        $sanitization = new MaxCharsCombinedSanitization(10, [
            'test.input1',
            'test.input2',
        ], '::');
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 'abcdef',
            'input2' => 'ghijkl',
        ]]);
        $this->assertEquals('abcdef', data_get($sanitized, 'test.input1'));
        // Only 2 chars because 6 (input1) + spacer (2) + 2 = 10 (max)
        $this->assertEquals('gh', data_get($sanitized, 'test.input2'));
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
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 2,
            'input2' => 5,
            'input3' => 3,
        ]]);
        $this->assertEquals(2, data_get($sanitized, 'test.input1'));
        $this->assertEquals(5, data_get($sanitized, 'test.input2'));
        $this->assertEquals(3, data_get($sanitized, 'test.input3'));

        // Test that wrong input gets corrected
        $sanitization = new MaxSumSanitization(10, [
            'test.input1',
            'test.input2',
            'test.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 3,
            'input2' => 5,
            'input3' => 3,
        ]]);
        $this->assertEquals(3, data_get($sanitized, 'test.input1'));
        $this->assertEquals(3, data_get($sanitized, 'test.input2'));
        $this->assertEquals(3, data_get($sanitized, 'test.input3'));
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
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 2,
            'input2' => 3,
            'input3' => 4,
        ]]);
        $this->assertEquals(2, data_get($sanitized, 'test.input1'));
        $this->assertEquals(3, data_get($sanitized, 'test.input2'));
        $this->assertEquals(4, data_get($sanitized, 'test.input3'));

        // Test that wrong input gets corrected
        $sanitization = new MaxMultipliedSanitization(23, [
            'test.input1',
            'test.input2',
            'test.input3',
        ]);
        $sanitized = $sanitization->sanitize('test.input', ['test' => [
            'input1' => 2,
            'input2' => 3,
            'input3' => 4,
        ]]);
        $this->assertEquals(2, data_get($sanitized, 'test.input1'));
        $this->assertEquals(2, data_get($sanitized, 'test.input2'));
        $this->assertEquals(2, data_get($sanitized, 'test.input3'));
    }
}