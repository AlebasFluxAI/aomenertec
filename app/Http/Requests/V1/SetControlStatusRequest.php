<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateSerialRule;

class SetControlStatusRequest extends FormRequest
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
        $flagId = 0;

        foreach ($alertConfigFrame as $item) {
            if (!in_array($item['variable_name'], ['network_operator_id', 'equipment_id', 'network_operator_new_id', 'equipment_new_id'])) {
                if ($flagId != $item['flag_id']) {
                    $rules[str_replace(["max_", "min_"], "status_", $item['variable_name'])] = 'required|numeric';
                    $flagId = $item['flag_id'];
                }
            }
        }

        return $rules;
    }
}
