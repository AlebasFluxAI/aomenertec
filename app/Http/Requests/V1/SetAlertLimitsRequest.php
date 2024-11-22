<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateSerialRule;

class SetAlertLimitsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        $rules = [
            'serial' => ['required', new ValidateSerialRule()],
        ];

        $alertConfigFrame = config('data-frame.alert_config_frame');

        foreach ($alertConfigFrame as $index => $item) {
            if (!in_array($item['variable_name'], ['network_operator_id', 'equipment_id', 'network_operator_new_id', 'equipment_new_id'])) {
                if (strpos($item['variable_name'], 'min') !== false) {
                    $rules[$item['variable_name']] = 'required|numeric|lte:' . $alertConfigFrame[$index - 1]['variable_name'];
                } else {
                    $rules[$item['variable_name']] = 'required|numeric';
                }
            }
        }

        return $rules;
    }
}
