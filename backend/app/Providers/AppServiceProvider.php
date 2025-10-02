<?php

namespace App\Providers;

use App\Contracts\EncryptorInterface;
use App\Contracts\HasherInterface;
use App\Services\AesEncryptor;
use App\Services\Sha256HasherWithPepper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EncryptorInterface::class, AesEncryptor::class);
        $this->app->bind(HasherInterface::class, Sha256HasherWithPepper::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Отключение обертки данных для API
        JsonResource::withoutWrapping();
    }
}
