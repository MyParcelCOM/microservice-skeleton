<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Arr;
use MyParcelCom\Microservice\Rules\Sanitization\SanitizationInterface;

class FormRequest extends BaseFormRequest
{
    /**
     * Prevents laravel from trying to interact with a session that isn't there.
     *
     * @var string
     */
    protected $redirect = '/shipments';

    /** @var bool $suspendValidation */
    protected $suspendValidation;

    /**
     * Constructor.
     * Uses constructor DI to set $suspendValidation variable
     *
     * @param boolean $suspendValidation
     */
    public function __construct(bool $suspendValidation = false)
    {
        $this->suspendValidation = $suspendValidation;

        parent::__construct();
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param array|mixed $keys
     * @return array
     */
    public function all($keys = null): array
    {
        $parameters = $this->getInputSource()->all();

        $parameters = $this->sanitize($parameters, $this->sanitization());
        $parameters = $this->sanitize($parameters, $this->sanitizationAfterValidation());

        $this->getInputSource()->replace($parameters);

        return parent::all($keys);
    }

    /**
     * @return array
     */
    protected function sanitization(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function sanitizationAfterValidation(): array
    {
        return [];
    }

    /**
     * @param array $parameters
     * @param array $sanitization
     * @return array
     */
    protected function sanitize(array $parameters, array $sanitization): array
    {
        foreach ($sanitization as $key => $callbacks) {
            // Check if there's only one sanitization rule
            if (!is_array($callbacks)) {
                $callbacks = [$callbacks];
            }

            foreach ($callbacks as $callback) {
                if ($callback instanceof SanitizationInterface) {
                    $parameters = $callback->sanitize((string) $key, $parameters, $this->shipmentRules());
                } elseif (is_array($callback) && count($callback) === 2 && is_string($callback[0]) && is_array($callback[1])) {
                    // Sanitization class is given as first item of array
                    // While sanitization parameters are given as second item
                    $sanitizer = new ($callback[0])(...$callback[1]);
                    $parameters = $sanitizer->sanitize((string) $key, $parameters, $this->shipmentRules());
                } elseif (!is_string($key)) {
                    // This is a more complex sanitization for multiple fields
                    // Since no specific field key has been specified
                    $parameters = call_user_func($callback, $parameters);
                } elseif ($value = Arr::get($parameters, $key)) {
                    Arr::set($parameters, $key, call_user_func($callback, $value, $parameters));
                }
            }
        }

        return $parameters;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        if ($this->suspendValidation) {
            return $this->defaultRules();
        }
        return array_merge($this->defaultRules(), $this->shipmentRules());
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        // Only use non-intrusive sanitization for validation
        $parameters = parent::all();
        return $this->sanitize($parameters, $this->sanitization());
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        // After validation has passed
        // We have to force the full sanitization
        $this->getInputSource()->replace($this->all());
    }

    /**
     * @return array
     */
    private function defaultRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function shipmentRules(): array
    {
        return [];
    }
}
