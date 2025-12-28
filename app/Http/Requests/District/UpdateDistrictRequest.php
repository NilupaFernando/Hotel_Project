<?php

namespace App\Http\Requests\District;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistrictRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('districts')->ignore($this->route('id')), // Correct way
            ],
            'about' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'image' => 'required',
            'travel_season' => 'nullable|string|max:500',
        ];
    }
}
