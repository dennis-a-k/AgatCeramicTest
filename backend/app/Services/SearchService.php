<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\SearchRepository;
use Illuminate\Http\Request;

class SearchService
{
    protected $repository;
    protected $searchRepository;

    public function __construct(ProductRepository $repository, SearchRepository $searchRepository)
    {
        $this->repository = $repository;
        $this->searchRepository = $searchRepository;
    }

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

    /**
     * Быстрый поиск для автодополнения
     *
     * @param string $query
     * @return array
     */
    public function quickSearch(string $query): array
    {
        return $this->searchRepository->quickSearch($query);
    }
}
