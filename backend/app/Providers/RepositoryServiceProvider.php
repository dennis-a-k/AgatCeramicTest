<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SearchRepository;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SearchService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BrandRepository::class, function ($app) {
            return new BrandRepository($app->make(\App\Models\Brand::class));
        });

        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository($app->make(\App\Models\Category::class));
        });

        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository($app->make(\App\Models\Product::class));
        });

        $this->app->bind(SearchRepository::class, function ($app) {
            return new SearchRepository($app->make(\App\Models\Product::class));
        });

        $this->app->bind(BrandService::class, function ($app) {
            return new BrandService($app->make(BrandRepository::class));
        });

        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CategoryRepository::class));
        });

        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class));
        });

        $this->app->bind(SearchService::class, function ($app) {
            return new SearchService($app->make(ProductRepository::class), $app->make(SearchRepository::class));
        });
    }

    public function boot()
    {
        //
    }
}
