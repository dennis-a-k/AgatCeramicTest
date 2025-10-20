<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function find($id): ?Category
    {
        return $this->model->with(['children', 'filterableAttributes'])
            ->where('is_active', true)
            ->find($id);
    }

    public function create(array $data): Category
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

    public function getChildrenBySlug($slug): Collection
    {
        $parent = $this->model->where('slug', $slug)->first();
        if (!$parent) {
            return collect();
        }
        return $this->model->where('parent_id', $parent->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
