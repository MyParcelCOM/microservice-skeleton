<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use MyParcelCom\Hermes\Http\ShipmentRequest;

class ApiRequestValidator
{
    /** @var string[] */
    protected $errors = [];

    /** @var RuleInterface[] */
    protected $rules = [];

    /**
     * @param ShipmentRequest $request
     * @return bool
     */
    public function validate(ShipmentRequest $request): bool
    {
        $requestData = \GuzzleHttp\json_decode($request->getContent());
        $rules = $this->getRules();

        array_walk($rules, function (RuleInterface $rule) use ($requestData) {
            if (!$rule->isValid($requestData)) {
                $errors = $rule->getErrors();

                $this->errors = array_merge($this->errors, $errors);
            }
        });

        return empty($this->errors);
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param RuleInterface[] $rules
     * @return $this
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param RuleInterface $rule
     * @return $this
     */
    public function addRule(RuleInterface $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
