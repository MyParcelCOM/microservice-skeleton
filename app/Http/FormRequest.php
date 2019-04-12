<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    public $redirect = '/shipments';

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge($this->defaultRules(), $this->carrierSpecificShipmentRules());
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
    protected function carrierSpecificShipmentRules(): array
    {
        return [];
    }
}
