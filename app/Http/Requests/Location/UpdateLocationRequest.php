<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string|max:20',
            'opening_hours' => 'sometimes|required|array',
            'opening_hours.*' => 'array',
            'map_url' => 'sometimes|required|url',
        ];
    }
}
