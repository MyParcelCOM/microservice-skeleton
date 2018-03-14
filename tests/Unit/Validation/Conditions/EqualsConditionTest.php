<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation\Conditions;

use MyParcelCom\Microservice\Validation\Conditions\EqualsCondition;
use PHPUnit\Framework\TestCase;

class EqualsConditionTest extends TestCase
{
    /** @test */
    public function testMeetsTrueCondition()
    {
        $condition = new EqualsCondition('this.path.should.be.equal', 'to.this.path');

        $data = (object)[
            'this' => (object)[
                'path' => (object)[
                    'should' => (object)[
                        'be' => (object)[
                            'equal' => 'YOLO',
                        ],
                    ],
                ],
            ],
            'to'   => (object)[
                'this' => (object)[
                    'path' => 'YOLO',
                ],
            ],
        ];

        $this->assertTrue($condition->meetsCondition($data));
    }

    /** @test */
    public function testMeetsFalseCondition()
    {
        $condition = new EqualsCondition('this.path.should.not.be.equal', 'to.this.path');

        $data = (object)[
            'this' => (object)[
                'path' => (object)[
                    'should' => (object)[
                        'not' => (object)[
                            'be' => (object)[
                                'equal' => 'YOLO',
                            ],
                        ],
                    ],
                ],
            ],
            'to'   => (object)[
                'this' => (object)[
                    'path' => 'YOLT',
                ],
            ],
        ];

        $this->assertFalse($condition->meetsCondition($data));
    }
}
