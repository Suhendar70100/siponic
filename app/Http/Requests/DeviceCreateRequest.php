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
            'garden_id' => ['required'],
            'max_ppm' => 'required|integer|min:0',
            'min_ppm' => 'required|integer|min:0',
            'plants' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'garden_id.required' => 'Nama perkebunan wajib diisi',
            'max_ppm.required' => 'Maksimal nutrisi wajib diisi',
            'max_ppm.integer' => 'Maksimal nutrisi harus berupa angka',
            'max_ppm.min' => 'Maksimal nutrisi harus berupa angka positif',
            'min_ppm.required' => 'Minimal nutrisi wajib diisi',
            'min_ppm.integer' => 'Minimal nutrisi harus berupa angka',
            'min_ppm.min' => 'Minimal nutrisi harus berupa angka positif',
            'plants.required' => 'Tanaman wajib diisi',
        ];
    }
}