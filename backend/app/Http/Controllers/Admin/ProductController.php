<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttributeValue;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;
    protected $searchService;

    public function __construct(ProductService $productService, SearchService $searchService)
    {
        $this->productService = $productService;
        $this->searchService = $searchService;
    }

    public function index(Request $request): JsonResponse
    {
        $request->merge(['admin' => true]);
        $result = $this->productService->getProductsWithFilters($request);
        return response()->json($result);
    }

    public function show($id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'article' => 'required|string|unique:products,article,' . $id,
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
            'attribute_values.*.id' => 'required|integer|exists:product_attribute_values,id,product_id,' . $id,
            'attribute_values.*.string_value' => 'nullable|string',
            'attribute_values.*.number_value' => 'nullable|numeric',
            'attribute_values.*.boolean_value' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        $updated = $this->productService->updateProduct($id, $validated);

        if (!$updated) {
            return response()->json(['message' => 'Product not found or could not be updated'], 404);
        }

        // Update attribute values
        if (isset($validated['attribute_values'])) {
            foreach ($validated['attribute_values'] as $attrValue) {
                ProductAttributeValue::where('id', $attrValue['id'])
                    ->update([
                        'string_value' => $attrValue['string_value'] ?? null,
                        'number_value' => $attrValue['number_value'] ?? null,
                        'boolean_value' => $attrValue['boolean_value'] ?? null,
                    ]);
            }
        }

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);

        if (!$deleted) {
            return response()->json(['message' => 'Product not found or could not be deleted'], 404);
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
