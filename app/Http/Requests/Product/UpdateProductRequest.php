<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_title' => 'sometimes|required|string|max:255',
            'product_description' => 'sometimes|required|string',
            'product_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_badge_popular' => 'sometimes|required|string',
        ];
    }
}
