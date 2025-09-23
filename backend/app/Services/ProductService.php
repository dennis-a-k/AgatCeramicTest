<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllProducts(): array
    {
        return $this->repository->all()->toArray();
    }

    public function getProductById($id): ?Product
    {
        return $this->repository->find($id);
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

    // ниже новое

    /**
     * Получение товаров по категории с фильтрами
     *
     * @param string $slug Slug категории
     * @param Request $request Параметры фильтрации
     * @return array
     */
    public function getProductsByCategory($slug, Request $request): array
    {
        return $this->repository->getByCategorySlug($slug, $request);
    }
}
