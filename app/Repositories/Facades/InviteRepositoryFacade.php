<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class InviteRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Invite';
    }
}