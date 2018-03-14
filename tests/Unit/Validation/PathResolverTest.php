<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Validation;

use MyParcelCom\Microservice\Validation\PathResolver;
use PHPUnit\Framework\TestCase;

class PathResolverTest extends TestCase
{
    /** @test */
    public function testResolve()
    {
        $resolver = new PathResolver();

        $data = (object)[
            'this'   => (object)[
                'is' => 'an',
            ],
            'object' => (object)[
                'with' => (object)[
                    'some' => (object)[
                        'random' => 'data',
                    ],
                ],
            ],
        ];

        $this->assertEquals('an', $resolver->resolve('this.is', $data));
        $this->assertEquals($data->object->with, $resolver->resolve('object.with', $data));
        $this->assertNull($resolver->resolve('this.is.not.a.valid.path', $data));
    }
}
