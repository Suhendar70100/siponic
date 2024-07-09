<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceUpdateRequest extends FormRequest
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
        $deviceId = $this->route('id'); // Assuming the route parameter is named 'device'

        return [
            'guid' => [
                'required',
                Rule::unique('device', 'guid')->ignore($deviceId),
                'regex:/^(?:[0-9A-Fa-f]{2}[:-]){5}(?:[0-9A-Fa-f]{2})$/'
            ], // Mac Address Format
            'note' => ['required'],
            'garden_id' => ['required'],
            'max_ppm' => ['required'],
            'min_ppm' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'guid.regex' => 'The guid must be in MAC address format',
        ];
    }
}
