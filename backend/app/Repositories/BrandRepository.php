<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Brand $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function find($id): ?Brand
    {
        return $this->model->where('is_active', true)->find($id);
    }

    public function create(array $data): Brand
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->find($id)->delete();
    }

    public function getByCategories(array $categorySlugs): Collection
    {
        return $this->model->select('brands.*')
            ->join('products', 'brands.id', '=', 'products.brand_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('categories.slug', $categorySlugs)
            ->where('categories.is_active', true)
            ->where('brands.is_active', true)
            ->where('products.is_published', true)
            ->distinct()
            ->orderBy('brands.name')
            ->get();
    }
}
