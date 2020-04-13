<?php

namespace App\Providers;

use App\Repositories\Interfaces\InviteRepositoryInterface;
use App\Repositories\Interfaces\MemberRequestRepositoryInterface;
use App\Repositories\InviteRepository;
use App\Repositories\MemberRequestRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MemberRequestRepositoryInterface::class, MemberRequestRepository::class);
        $this->app->bind(InviteRepositoryInterface::class, InviteRepository::class);
    }
}