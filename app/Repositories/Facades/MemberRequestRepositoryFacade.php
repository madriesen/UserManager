<?php


namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class MemberRequestRepositoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'MemberRequest';
    }
}