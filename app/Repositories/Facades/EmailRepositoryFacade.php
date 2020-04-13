<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class EmailRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Email';
    }
}