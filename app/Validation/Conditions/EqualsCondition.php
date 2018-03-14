<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation\Conditions;

use MyParcelCom\Microservice\Validation\PathResolver;
use stdClass;

class EqualsCondition implements ConditionInterface
{
    /** @var string */
    protected $pathA;

    /** @var string */
    protected $pathB;

    /**
     * @param string $pathA
     * @param string $pathB
     */
    public function __construct(string $pathA, string $pathB)
    {
        $this->pathA = $pathA;
        $this->pathB = $pathB;
    }

    /**
     * @param stdClass $data
     * @return bool
     */
    public function meetsCondition(stdClass $data): bool
    {
        $resolver = new PathResolver();

        return $resolver->resolve($this->pathA, $data) === $resolver->resolve($this->pathB, $data);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->pathA} equals {$this->pathB}";
    }
}
