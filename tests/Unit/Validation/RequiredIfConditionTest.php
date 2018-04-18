<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use Mockery;
use MyParcelCom\Microservice\Validation\Conditions\ConditionInterface;
use MyParcelCom\Microservice\Validation\RequiredIfCondition;
use PHPUnit\Framework\TestCase;

class RequiredIfConditionTest extends TestCase
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

        $validator = new RequiredIfCondition('some.path', $this->succeedingCondition);
        $this->assertFalse($validator->isValid($data));
        $this->assertNotEmpty($validator->getErrors());

        $validator = new RequiredIfCondition('some.path', $this->failingCondition);
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());

        $validator = new RequiredIfCondition('hey.you', $this->succeedingCondition);
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());

        $validator = new RequiredIfCondition('are', $this->failingCondition);
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());
    }
}
