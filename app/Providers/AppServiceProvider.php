<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\OrderService;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }


    public function boot(): void
    {
        //
    }
}
