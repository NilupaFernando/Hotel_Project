<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreHotelOwnerRequest extends FormRequest
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
            'nameAccount' => 'required|string|max:255',
            'emailAccount' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required|same:password',
            'registration_number' => 'required|string|unique:users,registration_number',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//            'registration_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'accommodation_type' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'province_name' => 'required|string',
            'category' => 'required',
            'district_id' => 'required|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'star' => 'nullable|numeric|in:1,2,3,4,5',
            'price_per_night' => 'nullable|numeric',
            'amenities' => 'nullable',
            'room_types' => 'nullable',
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email|unique:hotels',
            'website' => 'nullable|string',
            'images' => 'nullable|array',
            'thumbnail_image' => 'required',

            'max_adult' => 'nullable|integer|min:1',
            'max_children' => 'nullable|integer|min:0',
            'features' => 'nullable|string',
            'free_services' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0|max:100',        ];
    }
}
