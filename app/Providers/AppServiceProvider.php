<?php

namespace App\Providers;

use App\Mixins\ResponseMixins;
use App\Repositories\InviteRepository;
use App\Repositories\MemberRequestRepository;
use Illuminate\Support\Facades\Response;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Macros
        Response::mixin(new ResponseMixins());

        // Facades
        $this->app->singleton('MemberRequest', function ($app) {
            return new MemberRequestRepository();
        });
        $this->app->singleton('Invite', function ($app) {
            return new InviteRepository(new MemberRequestRepository());
        });
    }
}