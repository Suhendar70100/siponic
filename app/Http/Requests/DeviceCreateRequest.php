<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DeviceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'guid' => ['required', 'unique:device,guid', 'regex:/^(?:[0-9A-Fa-f]{2}[:-]){5}(?:[0-9A-Fa-f]{2})$/'], // Mac Address Format
            'note' => ['required'],
            'garden_id' => ['required'],
            'max_ppm' => ['required'],
            'min_ppm' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'guid.regex' => 'The guid must be mac address format',
        ];
    }
}