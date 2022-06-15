<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

class MultiColliShipmentRequest extends ShipmentRequest
{
    /**
     * Define callback functions to apply to the request data.
     * The original values will be overwritten by the callbacks.
     * By default all spaces will be removed from phone numbers.
     *
     * @return array
     */
    protected function sanitization(): array
    {
        $masterSanitization = collect(parent::sanitization())->mapWithKeys(function ($value, $key) {
            return [str_replace('data.attributes', 'data.master.attributes', (string) $key) => $value];
        });

        $colliSanitization = collect(parent::sanitization())->mapWithKeys(function ($value, $key) {
            return [str_replace('data.attributes', 'data.colli.*.attributes', (string) $key) => $value];
        });

        return $masterSanitization->merge($colliSanitization)->toArray();
    }

    /**
     * More intrusive sanitization rules should only modify the request data
     * after validation has already occured.
     *
     * @return array
     */
    protected function sanitizationAfterValidation(): array
    {
        $masterSanitization = collect(parent::sanitizationAfterValidation())->mapWithKeys(function ($value, $key) {
            $value = $this->rewriteSanitization($value, 'data.master.attributes');
            return [str_replace('data.attributes', 'data.master.attributes', (string) $key) => $value];
        });

        $colliSanitization = collect(parent::sanitizationAfterValidation())->mapWithKeys(function ($value, $key) {
            $value = $this->rewriteSanitization($value, 'data.colli.*.attributes');
            return [str_replace('data.attributes', 'data.colli.*.attributes', (string) $key) => $value];
        });

        return $masterSanitization->merge($colliSanitization)->toArray();
    }

    /**
     * Define the validation rules that apply to the request.
     * For example: [
     *   'data.attributes.description                   => 'required|string',
     *   'data.attributes.physical_properties.weight'   => 'integer|min:1000',
     * ]
     * This will enforce the description attribute to be set and to be a string
     * and that the weight of the shipment is an integer and is at least 1000.
     *
     * See the laravel documentation for all available validation rules:
     * https://laravel.com/docs/5.5/validation#available-validation-rules
     *
     * @return array
     */
    protected function shipmentRules(): array
    {
        $masterRules = collect(parent::shipmentRules())->mapWithKeys(function ($value, $key) {
            $value = $this->rewriteRules($value, 'data.master.attributes');
            return [str_replace('data.attributes', 'data.master.attributes', (string) $key) => $value];
        });

        // These are not required for the master shipment
        $masterRules->forget('data.master.attributes.physical_properties.weight');
        $masterRules->forget('data.master.attributes.physical_properties.length');
        $masterRules->forget('data.master.attributes.physical_properties.width');
        $masterRules->forget('data.master.attributes.physical_properties.height');

        $colliRules = collect(parent::shipmentRules())->mapWithKeys(function ($value, $key) {
            $value = $this->rewriteRules($value, 'data.colli.*.attributes');
            return [str_replace('data.attributes', 'data.colli.*.attributes', (string) $key) => $value];
        });

        // Description for colli items can be max 255 chars
        $colliRules['data.colli.*.attributes.description'] = ['required', 'string', 'max:255'];

        // These are optional, but still check if they are integer if filled in
        $colliRules['data.colli.*.attributes.physical_properties.length'] = ['integer'];
        $colliRules['data.colli.*.attributes.physical_properties.width'] = ['integer'];
        $colliRules['data.colli.*.attributes.physical_properties.height'] = ['integer'];

        return $masterRules->merge($colliRules)->toArray();
    }

    /**
     * @param mixed $rules
     * @param string $replacement
     * @return array
     */
    private function rewriteRules($rules, string $replacement): array
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }
        if (!is_array($rules)) {
            $rules = [$rules];
        }

        return collect($rules)->map(function ($rule) use ($replacement) {
            if (is_string($rule)) {
                return str_replace('data.attributes', $replacement, $rule);
            }
        })->toArray();
    }

    /**
     * @param mixed $rules
     * @param string $replacement
     * @return mixed
     */
    private function rewriteSanitization($rules, string $replacement)
    {
        if (is_array($rules)) {
            return collect($rules)->map(function ($rule) use ($replacement) {
                if (is_string($rule)) {
                    return str_replace('data.attributes', $replacement, $rule);
                }
                if (is_array($rule)) {
                    return $this->rewriteSanitization($rule, $replacement);
                }
                return $rule;
            })->toArray();
        }

        return $rules;
    }
}
