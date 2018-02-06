<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use Mockery;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Validation\ApiRequestValidator;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredIfPresentRule;
use MyParcelCom\Microservice\Validation\RequiredRule;
use MyParcelCom\Microservice\Validation\RuleInterface;

class ApiRequestValidatorTest extends TestCase
{
    /** @var Request */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = (Mockery::mock(Request::class))
            ->shouldReceive('getContent')
            ->andReturn(\GuzzleHttp\json_encode([
                'data' => (object)[
                    'attributes' => (object)[
                        'recipient_address'   => (object)[
                            'street_1'     => 'Hoofdweg 679',
                            'postal_code'  => '1324GA',
                            'city'         => 'Hoofddorp',
                            'country_code' => 'NL',
                        ],
                        'physical_properties' => (object)[
                            'weight' => 86,
                        ],
                    ],
                ],
            ]))
            ->getMock();
    }

    /** @test */
    public function testValidRequest()
    {
        $validator = (new ApiRequestValidator())
            ->addRule(new RequiredRule('data.attributes.recipient_address.postal_code'));

        $this->assertTrue($validator->validate($this->request));
        $this->assertEmpty($validator->getErrors());
    }

    /** @test */
    public function testInvalidRequest()
    {
        $validator = (new ApiRequestValidator())
            ->addRule(new RequiredIfPresentRule(
                'data.attributes.physical_properties.height',
                'data.attributes.physical_properties'
            ));

        $this->assertFalse($validator->validate($this->request));
        $this->assertNotEmpty($validator->getErrors());
    }

    /** @test */
    public function testRules()
    {
        $validator = new ApiRequestValidator();

        $rule_A = Mockery::mock(RuleInterface::class);
        $rule_B = Mockery::mock(RuleInterface::class);
        $rule_C = Mockery::mock(RuleInterface::class);

        $this->assertEmpty($validator->getRules());
        $this->assertInternalType('array', $validator->getRules());

        $validator->setRules([$rule_A, $rule_B]);
        $this->assertCount(2, $validator->getRules());
        $this->assertArraySubset([$rule_A, $rule_B], $validator->getRules());

        $validator->addRule($rule_C);
        $this->assertCount(3, $validator->getRules());
        $this->assertArraySubset([$rule_A, $rule_B, $rule_C], $validator->getRules());
    }

    /** @test */
    public function testGetErrors()
    {
        $requiredRule = Mockery::mock(RequiredRule::class, [
            'isValid'   => false,
            'getErrors' => ['Missing required property.'],
        ]);
        $requiredIfPresentRule = Mockery::mock(RequiredIfPresentRule::class, [
            'isValid'   => false,
            'getErrors' => ['Property is required when other property is present.'],
        ]);

        $validator = (new ApiRequestValidator())
            ->addRule($requiredRule)
            ->addRule($requiredIfPresentRule);

        $this->assertFalse($validator->validate($this->request));
        $this->assertInternalType('array', $validator->getErrors());
        $this->assertCount(2, $validator->getErrors());
        $this->assertContains(
            'Missing required property.',
            $validator->getErrors()
        );
        $this->assertContains(
            'Property is required when other property is present.',
            $validator->getErrors()
        );
    }
}
