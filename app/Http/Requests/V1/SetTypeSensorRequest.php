<?php

namespace App\Http\Requests\V1;

use App\Rules\ValidateSerialRule;
use Illuminate\Foundation\Http\FormRequest;

class SetTypeSensorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'serial' => ['required', new ValidateSerialRule()],
            'type' => ['required', 'in:1,2,3'], // Asegura que el tipo sea uno de los valores permitidos.
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'El campo "type" es obligatorio.',
            'type.in' => 'El valor del campo "type" debe ser uno de los siguientes: 1, 2 o 3.',
        ];
    }
}
