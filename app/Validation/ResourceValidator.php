<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\Microservice\Validation\RuleInterface;

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
     * @param $request
     * @return boolean
     */
    public function validate($request): bool
    {
        $rules = $this->getRules();

        array_walk($rules, function (RuleInterface $rule) use ($request) {
            $rule->isValid($request);
        });

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
