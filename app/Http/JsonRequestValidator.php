<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use JsonSchema\Validator;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use stdClass;

class JsonRequestValidator
{
    /** @var stdClass */
    protected $schema;

    /** @var Validator */
    protected $validator;

    /** @var Request */
    protected $request;

    /**
     * Validates currently set Request with schema for given path.
     *
     * @param string      $schemaPath
     * @param string|null $method
     * @param null|int    $status
     * @throws InvalidJsonSchemaException
     */
    public function validate(string $schemaPath, string $method = null, ?int $status = 200): void
    {
        $method = $method ?? strtolower($this->request->getRealMethod());

        if ($status !== null) {
            $schema = $this->schema->paths->{$schemaPath}->{$method}->responses->{$status}->schema;
        } else {
            $parameters = $this->schema->paths->{$schemaPath}->{$method}->parameters;

            $schema = $parameters[array_search('body', array_column($parameters, 'in'))]->schema;
        }

        $postData = json_decode($this->request->getContent());

        $this->validator->validate($postData, $schema);

        if ($this->validator->getErrors()) {
            throw new InvalidJsonSchemaException($this->validator->getErrors());
        }
    }

    /**
     * @param stdClass $schema
     * @return $this
     */
    public function setSchema(stdClass $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @param Validator $validator
     * @return $this
     */
    public function setValidator(Validator $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}
