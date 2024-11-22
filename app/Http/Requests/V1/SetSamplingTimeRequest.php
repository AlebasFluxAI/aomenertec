<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateSerialRule;

class SetSamplingTimeRequest extends FormRequest
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
        return [
            'serial' => ['required', new ValidateSerialRule()],
            'time_sampling_choice' => ['required', 'string', 'in:hourly,daily,monthly'],
            'data_per_interval' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $validIntervals = match ($this->time_sampling_choice) {
                    'hourly' => [1, 2, 3, 4, 6, 12, 60],
                    'daily' => [1, 2, 4, 8],
                    'monthly' => [1, 2],
                    default => [],
                };

                if (!in_array($value, $validIntervals)) {
                    $fail('Invalid data_per_interval value for the selected time_sampling_choice.');
                }
            }],
            'data_per_seconds' => ['required', 'numeric', 'between:0,254'],
        ];
    }
}
