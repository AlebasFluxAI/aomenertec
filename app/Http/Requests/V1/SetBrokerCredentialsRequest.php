<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateSerialRule;

class SetBrokerCredentialsRequest extends FormRequest
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
            'host' => 'required|string',
            'port' => 'required|string',
            'user' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
