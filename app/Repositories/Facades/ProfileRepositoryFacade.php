<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class ProfileRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Profile';
    }
}