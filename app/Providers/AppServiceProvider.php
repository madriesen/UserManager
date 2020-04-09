<?php

namespace App\Providers;

use App\Repositories\InviteRepository;
use App\Repositories\MemberRequestRepository;
use Illuminate\Support\ServiceProvider;
use function foo\func;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind('MemberRequest', function () {
//            return new MemberRequestRepository();
//        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('MemberRequest', function ($app) {
            return new MemberRequestRepository();
        });
        $this->app->singleton('Invite', function ($app) {
            return new InviteRepository(new MemberRequestRepository());
        });
    }
}