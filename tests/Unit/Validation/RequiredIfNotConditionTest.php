<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use Mockery;
use MyParcelCom\Microservice\Validation\Conditions\ConditionInterface;
use MyParcelCom\Microservice\Validation\RequiredIfNotCondition;
use PHPUnit\Framework\TestCase;

class RequiredIfNotConditionTest extends TestCase
{
    /** @var ConditionInterface */
    private $succeedingCondition;

    /** @var ConditionInterface */
    private $failingCondition;

    protected function setUp()
    {
        parent::setUp();

        $this->succeedingCondition = Mockery::mock(ConditionInterface::class, [
            'meetsCondition' => true,
            '__toString'     => 'succeeding condition',
        ]);

        $this->failingCondition = Mockery::mock(ConditionInterface::class, [
            'meetsCondition' => false,
            '__toString'     => 'failing condition',
        ]);
    }

    /** @test */
    public function testIsValid()
    {
        $data = (object)['hey' => (object)['you' => 'what'], 'are' => 'you', 'looking' => 'at?'];

        $validator = new RequiredIfNotCondition('some.path', $this->succeedingCondition);
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());

        $validator = new RequiredIfNotCondition('some.path', $this->failingCondition);
        $this->assertFalse($validator->isValid($data));
        $this->assertNotEmpty($validator->getErrors());

        $validator = new RequiredIfNotCondition('hey.you', $this->succeedingCondition);
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());

        $validator = new RequiredIfNotCondition('are', $this->failingCondition);
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());
    }
}
