<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article' => 'required|string|unique:products,article',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'product_code' => 'nullable|string',
            'description' => 'nullable|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:colors,id',
            'brand_id' => 'required|exists:brands,id',
            'is_published' => 'boolean',
            'is_sale' => 'boolean',
            'texture' => 'nullable|string',
            'pattern' => 'nullable|string',
            'country' => 'nullable|string',
            'collection' => 'nullable|string',
            'attribute_values' => 'nullable|array',
            'attribute_values.*.string_value' => 'nullable|string',
            'attribute_values.*.number_value' => 'nullable|numeric',
            'attribute_values.*.boolean_value' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*.sort_order' => 'required|integer|min:0',
            'new_images' => 'nullable|array',
            'new_images.*' => 'required|file|image|mimes:png,jpg,jpeg,webp|max:5120', // 5MB
        ];
    }
}
