<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'product_title' => $this->product_title,
            'product_description' => $this->product_description,
            'product_image' => $this->product_image ? asset(Storage::url($this->product_image)) : null,
            'product_badge_popular' => (bool) $this->product_badge_popular,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
