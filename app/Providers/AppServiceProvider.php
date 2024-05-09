<?php

namespace App\Providers;

use App\Libraries\Responders\ArrayResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Repositories\Auth\EloquentUserRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\UseCases\Auth\LoginUseCase;
use App\UseCases\Auth\RegisterUseCase;
use App\UseCases\Contracts\Users\LoginUseCaseInterface;
use App\UseCases\Contracts\Users\RegisterUseCaseInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Repositories

        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        //UseCases
        $this->app->bind(RegisterUseCaseInterface::class, RegisterUseCase::class);
        $this->app->bind(LoginUseCaseInterface::class, LoginUseCase::class);
        //Presentation Layer

        $this->app->bind(APIResponseInterface::class, ArrayResponse::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
