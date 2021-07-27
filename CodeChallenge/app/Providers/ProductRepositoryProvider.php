<?php

namespace App\Providers;

use App\Repository\ProductCsvRepository;
use App\Repository\ProductCsvRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ProductRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductCsvRepositoryInterface::class,ProductCsvRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ProductCsvRepositoryInterface::class,ProductCsvRepository::class);
    }
}
