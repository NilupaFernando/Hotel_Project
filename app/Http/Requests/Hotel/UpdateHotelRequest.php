<?php

namespace App\Http\Requests\Hotel;
use App\Models\Room;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'province_name' => 'required|string',
            'category' => 'required',
            'district_id' => 'required|integer',
            'accommodation_type' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'star' => 'nullable|numeric|in:1,2,3,4,5',
            'price_per_night' => 'nullable|numeric',
            'amenities' => 'nullable',
            'room_types' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $hotelId = $this->route('id');
                    $existingRoomTypes = Room::where('hotel_id', $hotelId)->pluck('type')->toArray();
                    $newRoomTypes = is_array($value) ? $value : explode(',', $value);
                    $newRoomTypes = array_map('trim', $newRoomTypes);
                    if (array_diff($existingRoomTypes, $newRoomTypes) || array_diff($newRoomTypes, $existingRoomTypes)) {
                        foreach ($existingRoomTypes as $roomType) {
                            if (!in_array($roomType, $newRoomTypes)) {
                                $fail("Cannot remove room type '$roomType' because it already exists in the rooms table. check room table");
                            }
                        }
                    }
                },
            ],
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => ['nullable', 'email', Rule::unique('hotels')->ignore($this->route('id')),],
            'existing_images' => 'nullable|array',
            'website' => 'nullable|string',
            'images' => 'nullable|array',
            'thumbnail_image' => 'required',
        ];
    }
}
