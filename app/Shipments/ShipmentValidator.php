<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class ResourceValidator
{
    /** @var array  */
    private $errors = [];

    private function getRules()
    {
        return [
            // TODO: Add validation rules.
        ];
    }

    /**
     * @param $resource
     * @return boolean
     */
    public function validate($resource): bool
    {
        $rules = $this->getRules();

        array_walk($rules, function ($rule, $attribute) use ($resource) {
            $this->validateAttribute($attribute, $rule, $resource);
        });

        return empty($this->errors);
    }

    private function validateAttribute($attribute, $rule, $resource)
    {
        if (is_callable($rule)) {
            return call_user_func($attribute, $rule, $resource);
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
