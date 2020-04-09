<?php


namespace App\Repositories\interfaces;


interface AccountRepositoryInterface
{
    public function createByInviteId(Int $invite_id);
}