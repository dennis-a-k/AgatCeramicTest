<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

class CategoryService
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories(): Collection
    {
        return $this->repository->all();
    }

    public function getCategoryById($id): ?Category
    {
        return $this->repository->find($id);
    }

    public function createCategory(array $data): Category
    {
        return $this->repository->create($data);
    }

    public function updateCategory($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteCategory($id): bool
    {
        return $this->repository->delete($id);
    }
}
