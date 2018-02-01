<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;


class RequiredRule extends ValidationRule implements RuleInterface
{
    /** @var string[] */
    protected $errors = [];

     /**
     * @param $resource
     * @return bool
     */
    public function isValid($resource): bool
    {
        $value = $this->getValueForPath($this->path, $resource);

        if (!isset($value)) {
            $this->errors[] = "Missing {$this->path} property on given resource object.";

            return false;
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}