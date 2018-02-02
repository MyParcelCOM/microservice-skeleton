<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use MyParcelCom\Microservice\Http\Request;

class ResourceValidator
{
    /** @var array  */
    protected $errors = [];

    /** @var RuleInterface[] */
    protected $rules = [];

    /**
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        $requestData = \GuzzleHttp\json_decode($request->getContent());
        $rules = $this->getRules();

        array_walk($rules, function (RuleInterface $rule) use ($requestData) {
            if (!$rule->isValid($requestData)) {
                $this->errors[] = $rule->getErrors();
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
