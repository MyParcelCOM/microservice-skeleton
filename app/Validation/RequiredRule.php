<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Validation;

use stdClass;

class RequiredRule extends ValidationRule implements RuleInterface
{
    /**
     * @param stdClass $requestData
     * @return bool
     */
    public function isValid(stdClass $requestData): bool
    {
        $value = $this->getValueForPath($this->path, $requestData);

        if (!isset($value)) {
            $this->errors[] = "Missing required {$this->path} on given request.";

            return false;
        }

        return true;
    }
}
