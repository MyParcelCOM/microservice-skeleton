<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Arr;

class FormRequest extends BaseFormRequest
{
    /**
     * Prevents laravel from trying to interact with a session that isn't there.
     *
     * @var string
     */
    protected $redirect = '/shipments';

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

        foreach ($this->sanitization() as $key => $callback) {
            $value = Arr::get($parameters, $key);
            if ($value) {
                Arr::set($parameters, $key, call_user_func($callback, $value));
            }
        }

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
    public function rules(): array
    {
        return array_merge($this->defaultRules(), $this->shipmentRules());
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
