<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttributeValue;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use App\Services\SearchService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;
    protected $searchService;
    protected $imageService;

    public function __construct(ProductService $productService, SearchService $searchService, ImageService $imageService)
    {
        $this->productService = $productService;
        $this->searchService = $searchService;
        $this->imageService = $imageService;
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
            'images' => 'nullable|array',
            'images.*.id' => 'nullable|integer|exists:product_images,id,product_id,' . $id,
            'images.*.sort_order' => 'required|integer|min:0',
            'new_images' => 'nullable|array',
            'new_images.*' => 'required|file|image|mimes:png,jpg,jpeg,webp|max:5120', // 5MB
        ]);

        $updated = $this->productService->updateProduct($id, $validated); // Обновляем все поля, кроме slug

        if (!$updated) {
            return response()->json(['message' => 'Product not found or could not be updated'], 404);
        }

        $product = $this->productService->getProductById($id); // Получаем свежий объект продукта

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Генерируем slug с id товара
        $slug = Str::slug($validated['name']) . '-' . $product->id;

        // Обновляем slug в продукте и сохраняем его
        $product->slug = $slug;
        $product->save();

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

        // Обработка изображений
        $this->handleProductImages($request, $id, $validated);

        return response()->json(['message' => 'Product updated successfully']);
    }

    protected function handleProductImages(Request $request, $productId, array $validated): void
    {
        $product = $this->productService->getProductById($productId);
        if (!$product) {
            return;
        }

        // Обновляем порядок существующих изображений
        if (isset($validated['images'])) {
            $this->imageService->updateImageOrder($productId, $validated['images']);

            // Получаем ID существующих изображений
            $existingImageIds = array_filter(array_column($validated['images'], 'id'));

            // Удаляем изображения, которые больше не в списке
            $imagesToDelete = ProductImage::where('product_id', $productId)
                ->whereNotIn('id', $existingImageIds)
                ->pluck('id')
                ->toArray();

            if (!empty($imagesToDelete)) {
                $this->imageService->deleteImages($imagesToDelete);
            }
        } else {
            // Если изображения не переданы, удаляем все
            $imagesToDelete = ProductImage::where('product_id', $productId)->pluck('id')->toArray();
            if (!empty($imagesToDelete)) {
                $this->imageService->deleteImages($imagesToDelete);
            }
        }

        // Сохраняем новые изображения
        if ($request->hasFile('new_images')) {
            $newFiles = $request->file('new_images');
            $this->imageService->saveProductImages($productId, $product->article, $newFiles);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
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
        ]);

        // Генерируем slug предварительно
        $slug = Str::slug($validated['name']);

        // Добавляем slug в валидированные данные
        $validated['slug'] = $slug;

        // Создаем продукт
        $product = $this->productService->createProduct($validated);

        // Обновляем slug с id товара
        $product->slug = $slug . '-' . $product->id;
        $product->save();

        // Создаем attribute values если переданы
        if (isset($validated['attribute_values'])) {
            foreach ($validated['attribute_values'] as $attrValue) {
                if (isset($attrValue['attribute_id'])) {
                    ProductAttributeValue::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attrValue['attribute_id'],
                        'string_value' => $attrValue['string_value'] ?? null,
                        'number_value' => $attrValue['number_value'] ?? null,
                        'boolean_value' => $attrValue['boolean_value'] ?? null,
                    ]);
                } elseif (isset($attrValue['id'])) {
                    // Обновляем существующие атрибуты
                    ProductAttributeValue::where('id', $attrValue['id'])
                        ->update([
                            'string_value' => $attrValue['string_value'] ?? null,
                            'number_value' => $attrValue['number_value'] ?? null,
                            'boolean_value' => $attrValue['boolean_value'] ?? null,
                        ]);
                }
            }
        }

        // Обработка изображений
        $this->handleProductImages($request, $product->id, $validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
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
