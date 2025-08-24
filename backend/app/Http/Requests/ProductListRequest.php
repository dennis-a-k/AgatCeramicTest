<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'brand' => 'sometimes|array',
            'brand.*' => 'integer|exists:brands,id',
            'price_min' => 'sometimes|numeric|min:0',
            'price_max' => 'sometimes|numeric|min:0|gte:price_min',
            'is_sale' => 'sometimes|boolean',
            'search' => 'sometimes|string|max:255',
            'sort' => 'sometimes|in:price_asc,price_desc,name_asc,name_desc,newest,oldest',
            'page' => 'sometimes|integer|min:1',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'search' => $this->search ? trim($this->search) : null,
        ]);
    }
}
