<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    protected $repository;
    protected $imageService;

    public function __construct(ProductRepository $repository, ImageService $imageService)
    {
        $this->repository = $repository;
        $this->imageService = $imageService;
    }

    public function getAllProducts(): array
    {
        return $this->repository->all()->toArray();
    }

    public function getAllProductSlugs(): array
    {
        return $this->repository->all()->pluck('slug')->toArray();
    }

    public function getProductById($id): ?Product
    {
        return $this->repository->find($id);
    }

    public function getProductBySlug($slug): ?Product
    {
        return $this->repository->findBySlug($slug);
    }

    public function getProductsWithFilters(Request $request): array
    {
        $products = $this->repository->applyFilters($request);
        $filters = $this->repository->getAvailableFilters($request);

        return [
            'products' => $products,
            'filters' => $filters
        ];
    }

    public function createProduct(array $data): Product
    {
        return $this->repository->create($data);
    }

    public function updateProduct($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteProduct($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Создать продукт с атрибутами и изображениями
     */
    public function createProductWithAttributesAndImages(array $data, Request $request): Product
    {
        try {
            return DB::transaction(function () use ($data, $request) {
                // Генерируем slug предварительно
                $slug = Str::slug($data['name']);
                $data['slug'] = $slug;

                // Создаем продукт
                $product = $this->repository->create($data);

                // Обновляем slug с id товара
                $product->slug = $slug . '-' . $product->id;
                $product->save();

                // Создаем attribute values если переданы
                if (isset($data['attribute_values'])) {
                    $this->createAttributeValues($product->id, $data['attribute_values']);
                }

                // Обработка изображений
                $this->handleProductImages($request, $product->id, $data);

                return $product;
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Обновить продукт с атрибутами и изображениями
     */
    public function updateProductWithAttributesAndImages($id, array $data, Request $request): bool
    {
        try {
            return DB::transaction(function () use ($id, $data, $request) {
                // Обновляем все поля, кроме slug
                $updated = $this->repository->update($id, $data);

                if (!$updated) {
                    return false;
                }

                $product = $this->repository->find($id);

                // Генерируем slug с id товара
                $slug = Str::slug($data['name']) . '-' . $product->id;
                $product->slug = $slug;
                $product->save();

                // Update attribute values
                if (isset($data['attribute_values'])) {
                    $this->updateAttributeValues($id, $data['attribute_values']);
                }

                // Обработка изображений
                $this->handleProductImages($request, $id, $data);

                return true;
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Создать значения атрибутов для продукта
     */
    protected function createAttributeValues(int $productId, array $attributeValues): void
    {
        foreach ($attributeValues as $attrValue) {
            if (isset($attrValue['attribute_id'])) {
                ProductAttributeValue::create([
                    'product_id' => $productId,
                    'attribute_id' => $attrValue['attribute_id'],
                    'string_value' => $attrValue['string_value'] ?? null,
                    'number_value' => $attrValue['number_value'] ?? null,
                    'boolean_value' => $attrValue['boolean_value'] ?? null,
                ]);
            }
        }
    }

    /**
     * Обновить значения атрибутов для продукта
     */
    protected function updateAttributeValues(int $productId, array $attributeValues): void
    {
        // Убираем дубликаты по attribute_id перед обработкой
        $uniqueAttributeValues = [];
        foreach ($attributeValues as $attrValue) {
            $attributeId = $attrValue['attribute_id'] ?? null;
            if ($attributeId && !isset($uniqueAttributeValues[$attributeId])) {
                $uniqueAttributeValues[$attributeId] = $attrValue;
            }
        }
        $attributeValues = array_values($uniqueAttributeValues);

        // Получаем существующие атрибуты для продукта
        $existingAttributes = ProductAttributeValue::where('product_id', $productId)->get()->keyBy('attribute_id');

        foreach ($attributeValues as $attrValue) {
            $attributeId = $attrValue['attribute_id'];

            if (isset($existingAttributes[$attributeId])) {
                // Обновляем существующий атрибут
                $existingAttributes[$attributeId]->update([
                    'string_value' => $attrValue['string_value'] ?? null,
                    'number_value' => $attrValue['number_value'] ?? null,
                    'boolean_value' => $attrValue['boolean_value'] ?? null,
                    'text_value' => $attrValue['text_value'] ?? null,
                ]);
            } else {
                // Создаем новый атрибут, если его нет
                ProductAttributeValue::create([
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'string_value' => $attrValue['string_value'] ?? null,
                    'number_value' => $attrValue['number_value'] ?? null,
                    'boolean_value' => $attrValue['boolean_value'] ?? null,
                    'text_value' => $attrValue['text_value'] ?? null,
                ]);
            }
        }
    }

    /**
     * Обработка изображений продукта
     */
    protected function handleProductImages(Request $request, int $productId, array $data): void
    {
        $product = $this->repository->find($productId);
        if (!$product) {
            return;
        }

        // Обновляем порядок существующих изображений
        if (isset($data['images'])) {
            $this->imageService->updateImageOrder($productId, $data['images']);

            // Получаем ID существующих изображений
            $existingImageIds = array_filter(array_column($data['images'], 'id'));

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
}
