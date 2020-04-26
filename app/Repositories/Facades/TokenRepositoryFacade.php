<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class TokenRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Token';
    }
}