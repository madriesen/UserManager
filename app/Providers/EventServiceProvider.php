<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // member_request
        'App\Events\MemberRequest\Created' => [
            'App\Listeners\MemberRequest\Created\CreateEmail',
        ],
        'App\Events\MemberRequest\Approved' => [
            'App\Listeners\MemberRequest\Approved\CreateInvite',
        ],
        // invites
        'App\Events\Invite\Created' => [
            'App\Listeners\Invite\Created\SendInviteEmail'
        ],
        'App\Events\Invite\Accepted' => [
            'App\Listeners\Invite\Accepted\CreateAccount',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
