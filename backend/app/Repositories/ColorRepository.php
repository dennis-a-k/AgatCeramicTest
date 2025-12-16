<?php

namespace App\Repositories;

use App\Models\Color;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ColorRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Color $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    public function find($id): ?Color
    {
        return $this->model->find($id);
    }

    public function create(array $data): Color
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
}
