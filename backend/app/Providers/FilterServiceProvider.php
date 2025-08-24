<?php

namespace App\Providers;

use App\Filters\BooleanFilter;
use App\Filters\NumberFilter;
use App\Filters\StringFilter;
use App\Services\ProductFilterService;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProductFilterService::class, function () {
            return new ProductFilterService(
                new StringFilter(),
                new NumberFilter(),
                new BooleanFilter()
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
