<?php

namespace App\Providers;

use App\Contracts\Repositories\User\UserRepoContracts;
use App\Repository\User\UserRepositories;
use App\Services\User\UserServices;
use \App\Contracts\Services\User\UserServiceContracts;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceContracts::class, UserServices::class);
        $this->app->bind(UserRepoContracts::class, UserRepositories::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
