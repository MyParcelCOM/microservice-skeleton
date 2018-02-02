<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use Mockery;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Validation\ResourceValidator;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Validation\RequiredIfPresentRule;
use MyParcelCom\Microservice\Validation\RequiredRule;
use MyParcelCom\Microservice\Validation\RuleInterface;

class ResourceValidatorTest extends TestCase
{
    /** @var Request */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = (Mockery::mock(Request::class))
            ->shouldReceive('getContent')
            ->andReturn(\GuzzleHttp\json_encode(
                (object)[
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
        $validator = (new ResourceValidator())
            ->addRule(new RequiredRule('data.attributes.recipient_address.postal_code'));

        $this->assertTrue($validator->validate($this->request));
        $this->assertEmpty($validator->getErrors());
    }

    /** @test */
    public function testInvalidRequest()
    {
        $validator = (new ResourceValidator())
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
        $validator = new ResourceValidator();

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
}
