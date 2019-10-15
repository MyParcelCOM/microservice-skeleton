<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Traits;

use JsonSchema\Validator;
use MyParcelCom\JsonApi\Traits\AssertionsTrait;
use stdClass;

/**
 * This trait should be used with a class that extends MyParcelCom\Microservice\Tests\TestCase.
 */
trait JsonApiAssertionsTrait
{
    use AssertionsTrait;

    /** @var stdClass */
    protected $schema;

    /** @var Validator */
    protected $validator;

    /**
     * @param string $schemaPath
     * @param string $method
     * @param int    $status
     * @return stdClass
     */
    protected function getSchema(string $schemaPath, string $method = 'get', int $status = 200, string $accept = 'application/vnd.api+json'): stdClass
    {
        if (!isset($this->schema)) {
            $this->schema = $this->app->make('schema');
        }

        // Check for old spec.
        if (isset($this->schema->swagger) && (int) $this->schema->swagger === 2) {
            return $this->schema->paths->{$schemaPath}->{strtolower($method)}->responses->{$status}->schema;
        }

        return $this->schema->paths->{$schemaPath}->{strtolower($method)}->responses->{$status}->content->{$accept}->schema;
    }

    /**
     * @return Validator
     */
    protected function getValidator()
    {
        if (isset($this->validator)) {
            return $this->validator;
        }

        return $this->validator = $this->app->make(Validator::class);
    }
}
