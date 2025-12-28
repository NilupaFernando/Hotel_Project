<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
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
            'hotel_id' => 'required|exists:hotels,id',
            'type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->where(function ($query) {
                    return $query->where('hotel_id', $this->hotel_id);
                }),
            ],
            'available_rooms' => 'required|integer|min:0',
            'max_adult' => 'nullable|integer|min:1',
            'max_children' => 'nullable',
            'price_per_adult' => 'nullable|numeric|min:0',
            'price_per_child' => 'nullable',
            'features' => 'nullable',
            'free_services' => 'nullable',
            'discount' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'guestOptions'=>'required'
        ];
    }
}


//'type' => [
//    'required',
//    'string',
//    'max:255',
//    Rule::unique('rooms')->where(function ($query) {
//        return $query->where('hotel_id', $this->hotel_id);
//    }),
//],
