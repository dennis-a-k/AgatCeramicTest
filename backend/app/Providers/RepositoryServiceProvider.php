<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ColorRepository;
use App\Repositories\FilterRepository;
use App\Repositories\FilterBuilder;
use App\Repositories\AvailableFiltersRetriever;
use App\Repositories\CategoryHelper;
use App\Repositories\Contracts\AvailableFiltersInterface;
use App\Repositories\Contracts\CategoryHelperInterface;
use App\Repositories\Contracts\FilterBuilderInterface;
use App\Repositories\ProductRepository;
use App\Repositories\SearchRepository;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\ProductService;
use App\Services\SearchService;
use App\Services\ImageService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BrandRepository::class, function ($app) {
            return new BrandRepository($app->make(Brand::class));
        });

        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository($app->make(Category::class));
        });

        $this->app->bind(ColorRepository::class, function ($app) {
            return new ColorRepository($app->make(Color::class));
        });

        $this->app->bind(CategoryHelperInterface::class, function ($app) {
            return new CategoryHelper();
        });

        $this->app->bind(FilterBuilderInterface::class, function ($app) {
            return new FilterBuilder(
                $app->make(Product::class),
                $app->make(CategoryHelperInterface::class)
            );
        });

        $this->app->bind(AvailableFiltersInterface::class, function ($app) {
            return new AvailableFiltersRetriever(
                $app->make(FilterBuilderInterface::class)
            );
        });

        $this->app->bind(FilterRepository::class, function ($app) {
            return new FilterRepository(
                $app->make(FilterBuilderInterface::class),
                $app->make(AvailableFiltersInterface::class),
                $app->make(CategoryHelperInterface::class)
            );
        });

        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository($app->make(Product::class), $app->make(FilterRepository::class));
        });

        $this->app->bind(SearchRepository::class, function ($app) {
            return new SearchRepository($app->make(Product::class));
        });

        $this->app->bind(BrandService::class, function ($app) {
            return new BrandService($app->make(BrandRepository::class));
        });

        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CategoryRepository::class));
        });

        $this->app->bind(ColorService::class, function ($app) {
            return new ColorService($app->make(ColorRepository::class));
        });

        $this->app->bind(ImageService::class, function ($app) {
            return new ImageService();
        });

        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class), $app->make(ImageService::class));
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
