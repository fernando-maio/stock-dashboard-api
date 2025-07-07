<?php

namespace App\Providers;

use App\Repositories\Contracts\StockApiInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\External\AlphaVantageApi;
use App\Repositories\External\TwelveDataApi;
use App\Services\Auth\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\StockServiceInterface;
use App\Services\Stock\StockService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
        // Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(StockServiceInterface::class, StockService::class);

        // Apis
        $this->app->bind(StockApiInterface::class, AlphaVantageApi::class);
        $this->app->bind(StockServiceInterface::class, function ($app) {
            return new StockService(
                $app->make(StockApiInterface::class),
                $app->make(TwelveDataApi::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
