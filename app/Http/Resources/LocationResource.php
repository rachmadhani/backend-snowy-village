<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_name' => $this->city_name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'opening_hours' => $this->opening_hours,
            'formatted_hours' => $this->formatted_hours, // From Model Accessor
            'map_url' => $this->map_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
