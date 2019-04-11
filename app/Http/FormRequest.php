<?php

declare(strict_types=1);

namespace MyParcelCom\Hermes\Http;

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
    public function messages(): array
    {
        return [
            'required' => 'The :attribute is required.',
            'email'    => 'The :attribute is not formatted as a valid email address.'
            // TODO: Add custom messages for all rules.
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'data.attributes.recipient_address.email' => 'recipient\'s email address',
            // TODO: Add a custom attribute for all shipment's attributes.
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge($this->getRules(), [
            // Default rules.
        ]);
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [];
    }
}
