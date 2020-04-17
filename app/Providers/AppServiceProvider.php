<?php

namespace App\Providers;

use App\Mixins\ResponseMixins;
use App\Repositories\AccountRepository;
use App\Repositories\AccountTypeRepository;
use App\Repositories\EmailRepository;
use App\Repositories\InviteRepository;
use App\Repositories\MemberRequestRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Response;
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
            return new InviteRepository();
        });
        $this->app->singleton('Account', function ($app) {
            return new AccountRepository();
        });
        $this->app->singleton('AccountType', function ($app) {
            return new AccountTypeRepository();
        });
        $this->app->singleton('Email', function ($app) {
            return new EmailRepository();
        });
        $this->app->singleton('Profile', function ($app) {
            return new ProfileRepository();
        });
    }
}