<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use JsonSchema\Exception\InvalidSchemaException;
use JsonSchema\Validator;
use MyParcelCom\JsonApi\Exceptions\InvalidHeaderException;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\JsonApi\Exceptions\ResourceConflictException;
use stdClass;

class JsonRequestValidator
{
    public function __construct(
        protected Request $request,
        protected stdClass $schema,
        protected Validator $validator,
    ) {
    }

    /**
     * Validates currently set Request with schema for given path.
     *
     * @throws InvalidJsonSchemaException
     * @throws ResourceConflictException
     */
    public function validate(string $schemaPath, ?string $method = null, ?string $accept = null): void
    {
        $method = $method ?? strtolower($this->request->getMethod());
        $accept = $accept ?? strtolower($this->request->header('Accept', 'application/vnd.api+json'));

        $schema = $this->getSchema($schemaPath, $method, $accept);

        $postData = json_decode($this->request->getContent());
        $this->validator->validate($postData, $schema);

        if ($this->validator->getErrors()) {
            if ($this->validator->getErrors()[0]['property'] === 'data.type') {
                throw new ResourceConflictException('type');
            } else {
                throw new InvalidJsonSchemaException($this->validator->getErrors());
            }
        }
    }

    /**
     * Get the schema for given path, method and accept header. Supports Swagger v2 and OpenAPI v3.
     */
    protected function getSchema(string $schemaPath, string $method, string $accept): stdClass
    {
        if (isset($this->schema->openapi) && (int) $this->schema->openapi === 3) {
            $content = $this->schema->paths->{$schemaPath}->{strtolower($method)}->requestBody->content;

            if (!isset($content->{$accept})) {
                throw new InvalidHeaderException('Accept header `' . $accept . '` is not supported');
            }

            return $content->{$accept}->schema;
        }

        if (isset($this->schema->swagger) && (int) $this->schema->swagger === 2) {
            $schemaParams = $this->schema->paths->{$schemaPath}->{$method}->parameters;
            foreach ($schemaParams as $schemaParam) {
                if ($schemaParam->in === 'body') {
                    return $schemaParam->schema;
                }
            }

            throw new InvalidSchemaException(
                sprintf(
                    'Could not find schema for path "%s" with method "%s" and accept header "%s"',
                    $schemaPath,
                    $method,
                    $accept,
                ),
            );
        }

        throw new InvalidSchemaException(
            'Used schema is of unknown version, expected "Swagger v2" or "OpenAPI v3"',
        );
    }
}
