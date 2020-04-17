<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class AccountTypeRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'AccountType';
    }
}