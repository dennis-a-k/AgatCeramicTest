<?php

namespace App\Services;

use App\Repositories\ColorRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Color;

class ColorService
{
    protected $repository;

    public function __construct(ColorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllColors(): Collection
    {
        return $this->repository->all();
    }

    public function getColorById($id): ?Color
    {
        return $this->repository->find($id);
    }

    public function createColor(array $data): Color
    {
        return $this->repository->create($data);
    }

    public function updateColor($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteColor($id): bool
    {
        return $this->repository->delete($id);
    }
}
