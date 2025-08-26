<?php

namespace App\Services;

use App\Repositories\BrandRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Brand;

class BrandService
{
    protected $repository;

    public function __construct(BrandRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBrands(): Collection
    {
        return $this->repository->all();
    }

    public function getBrandById($id): ?Brand
    {
        return $this->repository->find($id);
    }

    public function createBrand(array $data): Brand
    {
        return $this->repository->create($data);
    }

    public function updateBrand($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteBrand($id): bool
    {
        return $this->repository->delete($id);
    }
}
