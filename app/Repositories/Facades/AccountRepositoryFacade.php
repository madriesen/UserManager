<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class AccountRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Account';
    }
}